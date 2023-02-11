<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class GetBookingController extends AbstractController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private JWTTokenManagerInterface $JWTManager,
        private ManagerRegistry $managerRegistry,
    )
    {
    }

    public function __invoke()
    {
        try {
            $token = $this->tokenStorage->getToken();

            if (null === $token) {
                throw $this->createAccessDeniedException();
            }


            $dataUser =  $this->JWTManager->decode($this->tokenStorage->getToken());

            if (!array_key_exists('email', $dataUser)) {
                throw new \Exception('Key "email" does not exist in the array $dataUser');
            }

            $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $dataUser['email']]);


            $bookings = $this->managerRegistry->getRepository(Booking::class)->findBy(['client' => $user]);

            $data = [];
            foreach ($bookings as $booking) {
                $advertisement = $booking->getAdvertisement();
                $data[] = [
                    'booking' => $booking,
                    'advertisement' => $advertisement
                ];
            }

            return $this->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => $e], 500);
        }
    }
}
