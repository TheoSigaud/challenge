<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Service\ApiMailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;

#[AsController]
class CancelBookingController extends AbstractController
{
    public function __construct(
        private RequestStack    $requestStack,
        private ManagerRegistry $managerRegistry,
        private MailerInterface $mailer,
    )
    {
    }

    public function __invoke()
    {
        try {
            $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

            if(!$booking = $this->managerRegistry->getRepository(Booking::class)->findOneBy(['id' => $parameters['bookingId']])) {
                return $this->json(['message' => 'Not exist'], 401);
            }

            if ($booking->getStatus() === 0) {
                $advertisement = $booking->getAdvertisement();

                $booking->setStatus(1);
                $booking->setCancelUser($parameters['message']);
                $this->managerRegistry->getManager()->flush();

                $email = ApiMailerService::send_email(
                    $advertisement->getOwner()->getEmail(),
                    "Demande d'annulation",
                    'Une demande d\'annulation a été effectuée sur une de vos annonces',
                );

                $this->mailer->send($email);

                return $this->json(['message' => 'success'], 200);
            } else {
                return $this->json(['message' => 'Error'], 401);
            }

//            $booking = new Booking();
//            $booking->setStatus(0);
//            $booking->setClient($user);
//            $booking->setAdvertisement($advertisement);
//            $booking->setDateStart(new DateTime());
//            $booking->setDateEnd(new DateTime());
//            $booking->setCreatedAt(new \DateTimeImmutable());
//            $booking->setPayment($charge->id);
//
//
//            $entityManager = $this->managerRegistry->getManager();
//            $entityManager->persist($booking);
//            $entityManager->flush();



        } catch (\Exception $e) {
            return $this->json(['message' => 'Une erreur est survenue'], 500);
        }
    }
}
