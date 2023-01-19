<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class LoginController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $hasher,
    )
    {}

    public function __invoke()
    {
        $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if(!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $parameters['email']])) {
            throw new AccessDeniedHttpException();
        }

        if (!$this->hasher->isPasswordValid($user, $parameters['password'])) {
            throw new AccessDeniedHttpException();
        }

        if ($user->getStatus() === 0) {
            return $this->json([
                'message' => 'Not confirmed'
            ], 401);
        }

        return $this->json([
            'message' => 'Success'
        ], 200);
    }
}
