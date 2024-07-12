<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Property;
use App\Entity\PropertyType;
use App\Form\SearchPropertyType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/search')]
class SearchController extends AbstractController
{
    #[Route('/{type}/{city}', name: 'app_search_property_type_city', methods: ['GET', 'POST'], options: ["expose" => true])]
    #[Template('search/index.html.twig')]
    public function search(
        Request $request,
        PropertyType $type,
        City $city,
        PropertyRepository $propertyRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchData = [];
        $searchForm = $this->createForm(SearchPropertyType::class, null, [
            'currentPropertyType' => $type
        ]);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            if ($data['type']->getId() != $type->getId()) {
                return $this->redirectToRoute('app_search_property_type_city', [
                    'type' => $data['type']->getId(),
                    'city' => $city->getId()
                ]);
            }
            $searchData = $data;
        }
        $builder = $propertyRepository->buildFindByCityAndRoomQuery($city, $type, $searchData);
        $paginator = $paginator->paginate(
            $builder, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        $coordinates = array_map(
            fn (Property $item) => [
                'lat' => $item->getAddress()->getLatitude(),
                'lng' => $item->getAddress()->getLongitude(),
                'id' => $item->getId()
            ], 
            (array)$paginator->getItems()
        );

        return $this->render('search/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'pagination' => $paginator,
            'coordinates' => $coordinates
        ]);
    }
}
