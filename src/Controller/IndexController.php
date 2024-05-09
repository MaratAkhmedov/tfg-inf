<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/")]
class IndexController
{
    #[Route('/')]
    #[Template('index.html.twig')]
    public function twig() : array
    {
        return [];
    }
}
