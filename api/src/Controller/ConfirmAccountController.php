<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ConfirmAccountController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private RequestStack $requestStack
    )
    {}

    public function __invoke()
    {
        if(!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['token' => $this->requestStack->getCurrentRequest()->get('token')])) {
            throw $this->createNotFoundException();
        }

        $user->setToken(null);
        $user->setStatus(1);
        $user->setRoles(['ROLE_USER']);

        $this->managerRegistry->getManager()->flush();

        return $this->json('Success');
    }
}
