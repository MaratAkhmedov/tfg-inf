<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\PropertyRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class IndexController
{
    public function __construct(
        private CityRepository $cityRepository
    ) {
    }

    #[Route('/', 'default')]
    #[Template('index.html.twig')]
    public function index(): array
    {
        return [
            "cities" => $this->cityRepository->findAll()
        ];
    }

    #[Route('/search/{type}/{city}', name: 'app_search_property_type_city', methods: ['GET'], options: ["expose" => true])]
    #[Template('property/default.html.twig')]
    public function search(Request $request, string $type, City $city, PropertyRepository $propertyRepository): array
    {
        return [
            'properties' => $propertyRepository->findByCityAndRoom($city, $type)
        ];
    }

    #[Route('/phpinfo')]
    public function phpinfo(): Response
    {
        return new Response(phpinfo());
    }
}
