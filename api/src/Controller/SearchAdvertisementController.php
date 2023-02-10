<?php

namespace App\Controller;

use App\Entity\Advertisement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SearchAdvertisementController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private ManagerRegistry $managerRegistry
    )
    {}

    public function __invoke()
    {
        $city = $this->requestStack->getCurrentRequest()->get('city');
        $startDate = $this->requestStack->getCurrentRequest()->get('startDate');
        $endDate = $this->requestStack->getCurrentRequest()->get('endDate');

        $advertisements = $this->managerRegistry->getRepository(Advertisement::class)->findBy($city, $startDate, $endDate);

        return $this->json($advertisements);
    }
}
