<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocaleController extends AbstractController
{
    #[Route('/change-locale/{locale}', name: 'change_locale', options: ["expose" => true])]
    public function changeLocale(string $locale, Request $request): Response
    {
        // Set the locale in the session
        $request->getSession()->set('_locale', $locale);

        // Get the referer URL to redirect back to the previous page
        $referer = $request->headers->get('referer');
        if (!$referer) {
            $referer = $this->generateUrl('default');
        }

        return new RedirectResponse($referer);
    }
}