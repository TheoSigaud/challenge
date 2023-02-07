<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ResetPasswordController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private ManagerRegistry $managerRegistry
    )
    {}

    public function __invoke()
    {
        $email = json_decode($this->requestStack->getCurrentRequest()->getContent())->email;
        if(!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $email])) {
            throw $this->createNotFoundException();
        }

        $user->setToken(bin2hex(random_bytes(32)));
        $this->managerRegistry->getManager()->flush();

        // TODO : Send email with token


        return $this->json('Success');
    }
}
