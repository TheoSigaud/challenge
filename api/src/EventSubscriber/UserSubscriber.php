<?php
namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserSubscriber implements EventSubscriberInterface
{


    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW =>   ['setEncodedPassword', EventPriorities::PRE_WRITE]

        ];
    }

    public function setEncodedPassword(ViewEvent $event)
    {
        $user =  $event ->getControllerResult();
        $method = $event ->getRequest()->getMethod();
        if ($user instanceof User && $method === "POST")
        {
            $hash = $this->hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
        }
    }
}
