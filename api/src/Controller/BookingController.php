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
                || empty($parameters['cardCvv'])) {
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
            $advertisement = $this->managerRegistry->getRepository(Advertisement::class)->findOneBy(['id' => '1']);


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
                'amount' => 1000,
                'currency' => 'eur',
                'source' => $token,
            ]);

            $booking = new Booking();
            $booking->setStatus(0);
            $booking->setClient($user);
            $booking->setAdvertisement($advertisement);
            $booking->setDateStart(new DateTime());
            $booking->setDateEnd(new DateTime());
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
            return $this->json(['message' => 'Une erreur est survenue'], 500);
        }
    }
}
