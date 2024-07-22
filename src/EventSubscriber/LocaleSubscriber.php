<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'es')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // Try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('locale')) {
            $request->getSession()->set('locale', $locale);
        } else {
            // If no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // Must be registered after the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}