<?php

namespace App\Controller;

use App\Repository\AutonomousComunityRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class IndexController
{
    private AutonomousComunityRepository $autonomousComunityRepository;

    public function __construct(
        AutonomousComunityRepository $autonomousComunityRepository
    ) {
        $this->autonomousComunityRepository = $autonomousComunityRepository;
    }

    #[Route('/')]
    #[Template('index.html.twig')]
    public function index(): array
    {
        return [
            "autonomousComunities" => $this->autonomousComunityRepository->findAll()
        ];
    }

    #[Route('/phpinfo')]
    public function phpinfo(): Response
    {
        return new Response(phpinfo());
    }
}
