<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use App\Service\IPropertyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/property')]
class AdminPropertyController extends AbstractController
{    
    // TODO: ADMIN PROPERTY later add it to admin menu
    #[Route('/', name: 'app_property_index', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function admin(PropertyRepository $propertyRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('admin/properties.html.twig', [
            'properties' => $propertyRepository->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, IPropertyService $propertyService, SluggerInterface $slugger): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $property->setUser($this->getUser());
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

            $placeId = $form->get('mapPlaceId')->getData();
            $propertyService->generateProperty($property, $placeId);

            return $this->redirectToRoute('app_property_show', ['id' => $property->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request, Property $property, IPropertyService $propertyService, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        //TODO: check if is an owner
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           if($property->getType()->getName() != 'room') {
                $property->setRoom(null);
           }
            
            $files = $request->files->get('photos', []);

            
            // Handle uploaded files
            $files = $request->files->get('photos', []);
            foreach ($files as $file) {
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

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

                    $filePath = $this->getParameter('photos_directory').'/'.$filename;
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
        //TODO: check if is an owner
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($property);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
    }
}
