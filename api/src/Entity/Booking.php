<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\BookingController;
use App\Controller\CancelBookingController;
use App\Controller\GetBookingController;
use App\Controller\GetRefundController;
use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(operations: [
    new Post(
        name: 'buy',
        uriTemplate: '/buy',
        controller: BookingController::class
    ),

    new Post(
        name: 'cancel-booking',
        uriTemplate: '/cancel-booking',
        controller: CancelBookingController::class
    ),

    new Get(
        name: 'get-bookings',
        uriTemplate: '/get-bookings',
        controller: GetBookingController::class,
        read: false
    ),

    new Get(
        name: 'get-refunds',
        uriTemplate: '/get-refunds',
        controller: GetRefundController::class,
        read: false
    )
], routePrefix: '/api')]

#[ORM\Table(name: '`booking`')]
#[ORM\Entity(repositoryClass: BookingRepository::class)]

#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/admin/bookings',
        normalizationContext: ['groups' => ['booking:read']],
    )
])]

class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['booking:read', 'advertisement', 'bookings'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['booking:read', 'bookings'])]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column]
    #[Groups(['booking:read', 'advertisement'])]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[Groups(['booking:read', 'advertisement', 'bookings'])]
    private ?Advertisement $advertisement = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?User $client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['booking:read', 'bookings'])]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $payment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['booking:read'])]
    private ?string $cancel_user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['booking:read'])]
    private ?string $cancel_host = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): self
    {
        $this->advertisement = $advertisement;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCancelUser(): ?string
    {
        return $this->cancel_user;
    }

    public function setCancelUser(?string $cancel_user): self
    {
        $this->cancel_user = $cancel_user;

        return $this;
    }

    public function getCancelHost(): ?string
    {
        return $this->cancel_host;
    }

    public function setCancelHost(?string $cancel_host): self
    {
        $this->cancel_host = $cancel_host;

        return $this;
    }
}
