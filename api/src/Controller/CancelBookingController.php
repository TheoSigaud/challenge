<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Service\ApiMailerService;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Stripe\Refund;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class CancelBookingController extends AbstractController
{
    public function __construct(
        private RequestStack    $requestStack,
        private ManagerRegistry $managerRegistry,
        private MailerInterface $mailer,
        private JWTTokenManagerInterface $JWTManager,
        private TokenStorageInterface $tokenStorage
    )
    {
    }

    private function refund(string $id) {
        Stripe::setApiKey($_ENV['STRIPE_PRIVATE']);

        $refund = Refund::create([
            'charge' => $id,
        ]);
    }
    public function __invoke()
    {
        try {
            $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

            $dataUser =  $this->JWTManager->decode($this->tokenStorage->getToken());

            if (in_array('ROLE_ADMIN', $dataUser['roles'])){
                if(!$booking = $this->managerRegistry->getRepository(Booking::class)->findOneBy(['id' => $parameters['bookingId']])) {
                    return $this->json(['message' => 'Not exist'], 401);
                }
            }else{
                $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $dataUser['email']]);

                if(!$booking = $this->managerRegistry->getRepository(Booking::class)->findOneBy(['id' => $parameters['bookingId'], 'client' => $user])) {
                    return $this->json(['message' => 'Not exist'], 401);
                }
            }

            if ($booking->getStatus() === 0) {
                $currentDate = new \DateTimeImmutable();

                $diffDate = $currentDate->diff($booking->getDateStart());

                if ($diffDate->days < 3) {
                    $advertisement = $booking->getAdvertisement();

                    $booking->setStatus(1);
                    $booking->setCancelUser($parameters['message']);
                    $this->managerRegistry->getManager()->flush();

                    $email = ApiMailerService::send_email(
                        $advertisement->getOwner()->getEmail(),
                        "Demande d'annulation",
                        'Une demande d\'annulation a été effectuée sur une de vos annonces.
                        Message de l\'utilisateur : ' . $parameters['message'],
                    );

                    $this->mailer->send($email);
                } else {
                    $advertisement = $booking->getAdvertisement();

                    $booking->setStatus(-1);
                    $booking->setCancelUser($parameters['message']);
                    $this->managerRegistry->getManager()->flush();

                    $this->refund($booking->getPayment());

                    $email = ApiMailerService::send_email(
                        $advertisement->getOwner()->getEmail(),
                        "Annulation",
                        'Une annulation a été effectuée sur une de vos annonces.
                        Message de l\'utilisateur : ' . $parameters['message'],
                    );

                    $this->mailer->send($email);
                }

                return $this->json(['message' => 'success'], 200);
            } else if ($booking->getStatus() === 1) {
                if ($parameters['valueRefund']){
                    $booking->setStatus(-1);
                    $this->managerRegistry->getManager()->flush();

                    $this->refund($booking->getPayment());

                    $email = ApiMailerService::send_email(
                        $booking->getClient()->getEmail(),
                        "Annulation acceptée",
                        'Votre demande d\'annulation a été accepté. le remboursement est en cours.'
                    );

                    $this->mailer->send($email);

                    return $this->json(['message' => 'success'], 200);
                }else{
                    $booking->setStatus(2);
                    $booking->setCancelHost($parameters['message']);
                    $this->managerRegistry->getManager()->flush();

                    $email = ApiMailerService::send_email(
                        $booking->getClient()->getEmail(),
                        "Annulation refusée",
                        'Votre demande d\'annulation a été refusée. Elle a été envoyée à un administrateur pour vérification.
                        Message de l\'hôte : ' . $parameters['message'],
                    );

                    $this->mailer->send($email);

                    return $this->json(['message' => 'success'], 200);
                }
            } else if ($booking->getStatus() === 2) {
                if ($parameters['valueRefund']){
                    $booking->setStatus(-1);
                    $this->managerRegistry->getManager()->flush();

                    $this->refund($booking->getPayment());

                    $email = ApiMailerService::send_email(
                        $booking->getClient()->getEmail(),
                        "Annulation acceptée",
                        'Votre demande d\'annulation a été accepté. le remboursement est en cours.'
                    );

                    $this->mailer->send($email);

                    return $this->json(['message' => 'success'], 200);
                }else{
                    $booking->setStatus(3);
                    $booking->setCancelHost($parameters['message']);
                    $this->managerRegistry->getManager()->flush();

                    $email = ApiMailerService::send_email(
                        $booking->getClient()->getEmail(),
                        "Annulation refusée",
                        'Votre demande d\'annulation a été refusée par l\'administrateur.'
                    );

                    $this->mailer->send($email);

                    return $this->json(['message' => 'success'], 200);
                }
            } else {
                return $this->json(['message' => 'Error'], 401);
            }

        } catch (\Exception $e) {
            return $this->json(['message' => 'Une erreur est survenue'], 500);
        }
    }
}
