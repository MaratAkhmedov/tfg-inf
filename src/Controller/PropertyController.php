<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use App\Service\IPropertyService;
use App\Service\PropertyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/property')]
class PropertyController extends AbstractController
{
    #[Route('/search/{type}/{city}', name: 'app_property_type_city', methods: ['GET'], options: ["expose" => true])]
    public function search(Request $request, string $type, City $city, PropertyRepository $propertyRepository): Response
    {
        return $this->render('property/default.html.twig', [
            'properties' => $propertyRepository->findByCityAndRoom($city, $type),
        ]);
    }

    #[Route('/', name: 'app_property_default', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository): Response
    {
        return $this->render('property/default.html.twig', [
            'properties' => $propertyRepository->findAll(),
        ]);
    }

    // TODO: ADMIN PROPERTY later add it to admin menu
    #[Route('/admin', name: 'app_property_index', methods: ['GET'])]
    public function admin(PropertyRepository $propertyRepository): Response
    {
        return $this->render('property/index.html.twig', [
            'properties' => $propertyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IPropertyService $propertyService): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placeId = $form->get('mapPlaceId')->getData();
            
            $propertyService->generateProperty($property, $placeId);

            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('property/new.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_show', methods: ['GET'])]
    public function show(Property $property): Response
    {
        return $this->render('property/show.html.twig', [
            'property' => $property,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_property_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Property $property, IPropertyService $propertyService): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mapPlaceId = $form->get('mapPlaceId')->getData();
            $propertyService->generateProperty($property, $mapPlaceId);

            return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
        }

        if($address = $property->getAddress()) {
            $form->get('address')->setData($address->getFormattedAddress());
        }

        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_property_delete', methods: ['POST'])]
    public function delete(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($property);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_property_index', [], Response::HTTP_SEE_OTHER);
    }
}
