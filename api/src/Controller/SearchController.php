<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class SearchController extends AbstractController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private JWTTokenManagerInterface $JWTManager,
        private ManagerRegistry $managerRegistry,
        private RequestStack $requestStack,
    )
    {
    }

    public function __invoke()
    {

        try {
            $request = $this->requestStack->getCurrentRequest()->query;

            $query = $this->managerRegistry
                ->getManager()
                ->createQuery(
                    'SELECT b FROM App\Entity\Advertisement b
                        WHERE b.status > true
                        AND b.city = :city'
                )
                ->setParameter('date_start', $request->get('startDate'))
                ->setParameter('date_end', $request->get('startEnd'))
                ->setParameter('city', $request->get('city'));
            $advertisements = $query->getResult();

            return $this->json(['data' => $advertisements], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => $e], 500);
        }
    }
}
