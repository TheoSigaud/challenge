<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class CommentController extends AbstractController
{
    public function __construct(
        private RequestStack    $requestStack,
        private TokenStorageInterface $tokenStorage,
        private JWTTokenManagerInterface $JWTManager,
        private ManagerRegistry $managerRegistry,
    ){}


    /**
     * @Route("/comments/test", name="comments", methods={"GET", "POST"})
     */
    public function __invoke(Request $request)
    {
        $userId = $request->query->get('client');
        $advertisementId = $request->query->get('advertisement');

        $reservation = $this->managerRegistry->getRepository(Booking::class)
            ->findOneBy([
                'client' => $userId,
                'advertisement' => $advertisementId,
            ]);

        if (!$reservation) {
            throw new NotFoundHttpException();
        }

        $comment = new Comment();
//        $comment->setAdvertisement($advertisementId);

        return throw new NotFoundHttpException();

    }

}