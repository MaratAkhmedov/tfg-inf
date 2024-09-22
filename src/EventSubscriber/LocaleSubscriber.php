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

        if ($locale = $request->attributes->get('locale')) {
            $request->getSession()->set('locale', $locale);
        } else {
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