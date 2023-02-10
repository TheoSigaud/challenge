<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[AsController]
class LoginController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $hasher,
        private JWTTokenManagerInterface $JWTManager
    )
    {}

    public function __invoke()
    {
        $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if(!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $parameters['email']])) {
            throw new AccessDeniedHttpException();
        }

        if (!$this->hasher->isPasswordValid($user, $parameters['password'])
        || $user->getStatus() === 0) {
            return $this->json(['message' => 'Not confirmed'], 401);
        }

        return $this->json(['token' => $this->JWTManager->create($user)]);
    }
}