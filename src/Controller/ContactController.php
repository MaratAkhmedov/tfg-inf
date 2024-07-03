<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\Routing\Annotation\Route;

class ContactController
{
    #[Route('/contact', 'contact')]
    #[Template('contact.html.twig')]
    public function index(): array
    {
        return [];
    }
}
