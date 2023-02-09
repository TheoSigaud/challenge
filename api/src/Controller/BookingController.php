<?php

namespace App\Controller;

use App\Entity\Booking;
use DateTime;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class BookingController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private RequestStack    $requestStack
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

//            $booking = new Booking();
//            $booking->setAdvertisement('1');
//            $booking->setStatus(1);
//            $booking->setClient('1');
//

            return $this->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Une erreur est survenue'], 500);
        }
    }
}
