<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Booking;
use App\Entity\User;
use App\Service\ApiMailerService;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class BookingController extends AbstractController
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

            if (empty($parameters['cardNumber'])
                || empty($parameters['cardMonth'])
                || empty($parameters['cardYear'])
                || empty($parameters['cardCvv'])
                || empty($parameters['idAdvertisement'])
                || empty($parameters['dateStart'])
                || empty($parameters['dateEnd'])) {
                return $this->json(['message' => 'Les données de la carte sont manquantes'], 400);
            }

            $cardNumber = trim($parameters['cardNumber']);
            $cardMonth = trim($parameters['cardMonth']);
            $cardYear = trim($parameters['cardYear']);
            $cardCvv = trim($parameters['cardCvv']);

            if (!preg_match('/^[0-9]{16}$/', $cardNumber)) {
                return $this->json(['message' => 'Le numéro de carte est invalide'], 400);
            }

            $current_date = new DateTime();
            $card_expire = new DateTime($cardYear . "-" . $cardMonth . "-01");

            if ($card_expire < $current_date) {
                return $this->json(['message' => 'La date d\'expiration de la carte est dépassée'], 400);
            }


            if (!preg_match('/^[0-9]{3}$/', $cardCvv)) {
                return $this->json(['message' => 'Le code de sécurité de la carte est invalide'], 400);
            }

            $token = $this->tokenStorage->getToken();

            if (null === $token) {
                throw $this->createAccessDeniedException();
            }

            $dataUser =  $this->JWTManager->decode($this->tokenStorage->getToken());

            $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $dataUser['email']]);
            $advertisement = $this->managerRegistry->getRepository(Advertisement::class)->findOneBy(['id' => strval($parameters['idAdvertisement'])]);

            $date1 = new DateTime($parameters['dateStart']);
            $date2 = new DateTime($parameters['dateEnd']);
            $interval = $date1->diff($date2);
            $nights = $interval->days;

            $query = $this->managerRegistry
                ->getManager()
                ->createQuery(
                    'SELECT b FROM App\Entity\Booking b
                        WHERE b.status > 0
                        AND (b.date_start BETWEEN :date_start AND :date_end
                        OR b.date_end BETWEEN :date_start AND :date_end
                        OR (b.date_start <= :date_start AND b.date_end >= :date_end))'
                )
                ->setParameter('date_start', $parameters['dateStart'])
                ->setParameter('date_end', $parameters['dateEnd']);

            $existingBookings = $query->getResult();

            if (count($existingBookings) > 0) {
                return $this->json(['message' => 'Ces dates ne sont pas valides'], 500);
            }

            Stripe::setApiKey($_ENV['STRIPE_PRIVATE']);

            $token = Token::create([
                'card' => [
                    'number' => $cardNumber,
                    'exp_month' => $cardMonth,
                    'exp_year' => $cardYear,
                    'cvc' => $cardCvv,
                ],
            ]);


            $charge = Charge::create([
                'amount' => $advertisement->getPrice() * $nights * 100,
                'currency' => 'eur',
                'source' => $token,
            ]);

            $booking = new Booking();
            $booking->setStatus(0);
            $booking->setClient($user);
            $booking->setAdvertisement($advertisement);
            $booking->setDateStart($parameters['dateStart']);
            $booking->setDateEnd($parameters['dateEnd']);
            $booking->setCreatedAt(new \DateTimeImmutable());
            $booking->setPayment($charge->id);


            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            $email = ApiMailerService::send_email(
                $user->getEmail(),
                "Votre réservation",
                'Merci pour votre réservation',
            );

            $this->mailer->send($email);

            return $this->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => $e], 500);
        }
    }
}
