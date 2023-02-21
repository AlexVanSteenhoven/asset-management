<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function onLogoutEvent(LogoutEvent $event)
    {
        $session = new Session();

        $session->getFlashBag()->add('logout_success', [
            "title" => "Successfully logged out!",
            "message" => "You successfully logged out of your session! Now you can login again"
        ]);
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
