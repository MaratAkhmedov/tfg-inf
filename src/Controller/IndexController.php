<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/phpinfo')]
    public function phpinfo(): Response
    {
        return new Response(phpinfo());
    }
}
