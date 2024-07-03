<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\Routing\Annotation\Route;

class AboutController
{
    #[Route('/about', 'about_us')]
    #[Template('about.html.twig')]
    public function index(): array
    {
        return [];
    }
}
