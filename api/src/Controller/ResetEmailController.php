<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\ApiMailerService;
use App\Service\GenerateTokenService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;

#[AsController]
class ResetEmailController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private RequestStack $requestStack,
        private MailerInterface $mailer,
        private GenerateTokenService $generateTokenService
    )
    {}

    public function __invoke()
    {
        $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if(!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $parameters['email']])) {
            return $this->json(['message' => 'Success']);
        }

        $token = $this->generateTokenService->generateToken($user->getEmail(), '2021-06-01');
        $user->setToken($token);

        $email = ApiMailerService::send_email(
            $user->getEmail(),
            "Réinitialisation de votre compte",
            'Bonjour, voici le lien réinitialiser votre compte : http://'. $_SERVER['SERVER_NAME'] . ':8081/reset-password?token=' . $token,
        );

        $this->mailer->send($email);
        $this->managerRegistry->getManager()->flush();


        return $this->json(['message' => 'Success']);
    }
}