<?php
// api/src/EventSubscriber/BookMailSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Rate;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserSubscriber implements EventSubscriberInterface
{


    public function __construct(UserPasswordHasherInterface $hasher){}

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW =>   ['setEncodedPAssword', EventPriorities::PRE_WRITE]

        ];
    }

    public function setEncodedPAssword(ViewEvent $event)
    {
        /** @var Rate $rate */
        $rate = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$rate instanceof Rate || Request::METHOD_POST !== $method) {
            return;
        }
        $joke = $rate->getJoke();

        /** @var Rate $currentRate */
        $total = $rate->getStar();
        foreach ($joke->getRates() as $currentRate){
            $total += $currentRate->getStar();
        }
        $rate->getJoke()->setRatesTotal($total/count($joke->getRates()));

        dump($rate, $joke);
    }
}
