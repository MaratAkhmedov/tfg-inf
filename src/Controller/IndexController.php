<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\PropertyRepository;
use App\Repository\PropertyTypeRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class IndexController
{
    public function __construct(
        private CityRepository $cityRepository,
        private PropertyTypeRepository $propertyTypeRepository
    ) {
    }

    #[Route('/', 'default')]
    #[Template('index.html.twig')]
    public function index(): array
    {
        return [
            "propertyTypes" => $this->propertyTypeRepository->findAll(),
            "cities" => $this->cityRepository->findAll()
        ];
    }

    #[Route('/phpinfo')]
    public function phpinfo(): Response
    {
        return new Response(phpinfo());
    }
}
