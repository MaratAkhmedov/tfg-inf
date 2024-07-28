<?php

namespace App\Controller;

use Exception;
use App\Entity\Photo;
use App\Entity\Property;
use App\Form\PropertyType;
use Psr\Log\LoggerInterface;
use App\Service\IPropertyService;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('owner/property')]
#[IsGranted('ROLE_OWNER')]
class PropertyController extends AbstractController
{
    #[Route('/', name: 'app_property_index', methods: ['GET'])]
    public function index(Request $request, PropertyRepository $propertyRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
            
        // Render twig template
        return $this->render('admin/properties.html.twig', [
            'pagination' => $paginator = $paginator->paginate(
                $propertyRepository->findUserProperties($user),
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            ),
        ]);
    }

    #[Route('/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IPropertyService $propertyService, SluggerInterface $slugger, LoggerInterface $logger): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { /* This code is triggered when the user saves the property  */
            $property->setUser($this->getUser());

            try {
                $this->handleNewFilesUpload($request, $slugger, $property); /* upload files to the server */
            } catch (FileException $e) {
                $logger->error("File was not uploaded", [
                    "message" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine()
                ]);
            }

            $placeId = $form->get('mapPlaceId')->getData();
            $propertyService->generateProperty($property, $placeId);

            // Redirect to created Property
            return $this->redirectToRoute('app_property_show', ['id' => $property->getId()], Response::HTTP_SEE_OTHER);
        }

        // Render twig template
        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    private function handleNewFilesUpload(Request $request, SluggerInterface $slugger, Property $property)
    {
        $files = $request->files->get('photos', []);
        foreach ($files as $file) {
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $photosDirectory = $this->getParameter('photos_directory');
                $file->move(
                    $photosDirectory,
                    $newFilename
                );

                $photo = new Photo();
                $photo->setUrl($this->getParameter('photos_prefix') . '/' . $newFilename);
                $property->addPhoto($photo);
            }
        }
    }

    #[Route('/{id}/edit', name: 'app_property_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Property $property, IPropertyService $propertyService, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()->getUserIdentifier() != $property->getUser()->getEmail()) {
            throw new NotFoundHttpException("The property with id '" . $property->getId() . "' does not exist");
        }

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($property->getType()->getName() != 'room') {
                $property->setRoom(null);
            }

            $files = $request->files->get('photos', []);


            // Handle uploaded files
            $files = $request->files->get('photos', []);
            foreach ($files as $file) {
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    try {
                        $file->move(
                            $this->getParameter('photos_directory'),
                            $newFilename
                        );

                        $photo = new Photo();
                        $photo->setUrl($this->getParameter('photos_prefix') . '/' . $newFilename);
                        $property->addPhoto($photo);
                    } catch (FileException $e) {
                        // Handle exception if something happens during file upload
                    }
                }
            }

            // Handle deleted files
            $deletedFiles = json_decode($request->request->get('deletedFiles', '[]'), true);
            foreach ($deletedFiles as $filename) {
                $photo = $entityManager->getRepository(Photo::class)->findOneBy(['url' => $filename]);

                if ($photo) {
                    $property->removePhoto($photo);
                    $entityManager->remove($photo);

                    $filePath = $this->getParameter('photos_directory') . '/' . $filename;
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the file from the server
                    }
                }
            }


            $mapPlaceId = $form->get('mapPlaceId')->getData();
            $propertyService->generateProperty($property, $mapPlaceId);

            return $this->redirectToRoute('app_property_show', ['id' => $property->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($address = $property->getAddress()) {
            $form->get('address')->setData($address->getFormattedAddress());
        }

        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($property->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            throw new Exception("Not authorized");
        }

        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($property);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
    }
}
