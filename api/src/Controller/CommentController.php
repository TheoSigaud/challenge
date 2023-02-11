<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentController extends AbstractController
{
    public function __construct(
        private RequestStack    $requestStack,
        private TokenStorageInterface $tokenStorage,
        private JWTTokenManagerInterface $JWTManager,
        private ManagerRegistry $managerRegistry,
        private MailerInterface $mailer,
    )
    {
    }

    public function __invoke()
    {
        try {
            $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
            dd($parameters);
            return $this->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Une erreur est survenue'], 500);
        }
    }

}