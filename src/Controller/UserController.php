<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\RoleType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

#[Route(path: '/user')]
class UserController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier,
        protected TokenStorageInterface $tokenStorage
    ){}

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->get('role')->getData();
            if ($role == RoleType::Renter) {
                $user->setRoles(['ROLE_USER']);
            } else if ($role == RoleType::Owner) {
                $user->setRoles(['ROLE_OWNER']);
            }

            $file = $request->files->get('photo', []);
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('user_photos_directory'),
                        $newFilename
                    );
                    $owner = $user->getOwnerData();
                    $owner->setPhotoUrl($this->getParameter('user_photos_prefix') . '/' . $newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('igugle4@gmail.com', 'Marat Akhmedov'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('user/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('default');
        }

        return $this->render('user/register.html.twig', [
            'userForm' => $form,
        ]);
    }

    #[Route('/profile/edit', name: 'app_user_profile_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(Request $request, UserRepository $userRepository, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(['email' => $user->getUserIdentifier()]);
        //TODO: check if is an owner
        $form = $this->createForm(UserType::class, $user, [
            'isEdit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->get('role')->getData();
            if ($role == RoleType::Renter) {
                $user->setRoles(['ROLE_USER']);
            } else if ($role == RoleType::Owner) {
                $user->setRoles(['ROLE_OWNER']);
            }
            // Handle uploaded files
            $file = $request->files->get('photo', null);
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('user_photos_directory'),
                        $newFilename
                    );
                    $owner = $user->getOwnerData();
                    $owner->setPhotoUrl($this->getParameter('user_photos_prefix') . '/' . $newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }

            // Handle deleted files
            $deletedFiles = json_decode($request->request->get('deletedFiles', '[]'), true);
            foreach ($deletedFiles as $filename) {
                $filePath = $this->getParameter('public_directory').'/'.$filename;
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file from the server
                }
            }

            //prevent logout on user data update
            $this->tokenStorage->setToken(
                new UsernamePasswordToken($user, 'main', $user->getRoles())
            );

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('user/profile.html.twig', [
            'userForm' => $form,
            'fotoUrl' => $user->getOwnerData() ? $user->getOwnerData()->getPhotoUrl() : "",
            'role' => in_array('ROLE_OWNER', $user->getRoles()) ? 'owner' : 'renter',
            'isEdit' => true
        ]);
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/owner/{id}/contact', name: 'app_get_owner_contact_data', options: ["expose" => true])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getOwnerData(Request $request, User $propertyOwner, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // TODO: Check if can access property data
        $email = $propertyOwner->getUserIdentifier();
        $ownerData = $propertyOwner->getOwnerData();
        //$phone = $ownerData->getPh
        //$ownerData->getUserIdentifier();

        return new JsonResponse();
    }
}
