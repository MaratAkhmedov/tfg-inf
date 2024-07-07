<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\PropertyType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/search')]
class SearchController extends AbstractController
{
    #[Route('/{type}/{city}', name: 'app_search_property_type_city', methods: ['GET'], options: ["expose" => true])]
    #[Template('search.html.twig')]
    public function search(
        Request $request,
        PropertyType $type,
        City $city,
        PropertyRepository $propertyRepository,
        PaginatorInterface $paginator
    ): array {
        $builder = $propertyRepository->buildFindByCityAndRoomQuery($city, $type);
        return [
            'pagination' => $paginator->paginate(
                $builder, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                12 /*limit per page*/
            )
        ];
    }
}
