<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class ResetPasswordController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private ManagerRegistry $managerRegistry,
        private UserPasswordHasherInterface $hasher,
    )
    {}

    public function __invoke()
    {
        if(!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['token' => json_decode($this->requestStack->getCurrentRequest()->getContent())->token])) {
            throw $this->createNotFoundException();
        }

        $user->setToken(null);
        $hash = $this->hasher->hashPassword($user, json_decode($this->requestStack->getCurrentRequest()->getContent())->password);

        $user->setPassword($hash);

        $this->managerRegistry->getManager()->flush();

        return $this->json('Success');
    }
}
