<?php

namespace App\Controller;

use App\Entity\City;
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
        $searchForm = $this->createForm(SearchPropertyType::class);
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
        
        return $this->render('search/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'pagination' => $paginator->paginate(
                $builder, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                12 /*limit per page*/
            )
        ]);
    }
}
