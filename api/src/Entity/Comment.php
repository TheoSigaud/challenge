<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[ApiResource]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: '`comments`')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255,
     *     minMessage="The review must be at least {{ limit }} characters long",
     *     maxMessage="The review cannot be longer than {{ limit }} characters",
     *     )
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min=1, max=5,
     *     notInRangeMessage="Rate must be between {{ min }} and {{ max }}.")
     * @Assert\Type(
     *     type="integer",
     *     message="The rate must be between 1 and 5",
     *     )
     */
    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Advertisement $advertisement = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3, max=30,
     *     minMessage="The title must be at least {{ limit }} characters long",
     *     maxMessage="The title cannot be longer than {{ limit }} characters",
     *     )
     */
    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $status = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct() {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

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

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): self
    {
        $this->advertisement = $advertisement;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTimeImmutable();;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }


}
