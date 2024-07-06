<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/search')]
class SearchController extends AbstractController
{
    #[Route('/{type}/{city}', name: 'app_search_property_type_city', methods: ['GET'], options: ["expose" => true])]
    #[Template('default.html.twig')]
    public function search(Request $request, PropertyType $type, City $city, PropertyRepository $propertyRepository): array
    {
        return [
            'properties' => $propertyRepository->findByCityAndRoom($city, $type)
        ];
    }
}
