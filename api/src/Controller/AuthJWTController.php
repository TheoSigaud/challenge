<?php
namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class AuthJWTController extends AbstractController
{
    public function __construct(
        private JWTTokenManagerInterface $JWTManager,
        private TokenStorageInterface $tokenStorage
    )
    {
    }

    public function __invoke()
    {
        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            return $this->json(['data' => $this->JWTManager->decode($this->tokenStorage->getToken())]);
        } else {
            throw $this->createAccessDeniedException();
        }
    }
}