<?php
namespace App\EventSubscriber;

use AllowDynamicProperties;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Service\ApiMailerService;
use App\Service\GenerateTokenService;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
#[AllowDynamicProperties] final class UserSubscriber implements EventSubscriberInterface
{


    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private MailerInterface $mailer,
        private GenerateTokenService $generateTokenService
    )
    {}

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

            $token = $this->generateTokenService->generateToken($user->getEmail(), date("Y-m-d"));
            $user->setToken($token);

            $email = ApiMailerService::send_email(
                                $user->getEmail(),
                                "Création de votre compte",
                                'Bonjour, voici le lien pour valider votre compte : http://'. $_SERVER['SERVER_NAME'] . ':8081/confirm-account?token=' . $token,
                            );

            $this->mailer->send($email);
        }
    }
}
