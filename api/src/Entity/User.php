<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\LoginController;
use App\Controller\ResetPasswordController;
use App\Controller\ConfirmAccountController;
use App\Controller\CheckTokenController;
use App\Controller\ResetEmailController;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



#[GetCollection(
    normalizationContext: ['groups' => ['advertisement', 'owner']], routePrefix: '/admin'
)]
#[ApiResource(normalizationContext: ['groups' => ['advertisement', 'owner']], routePrefix: '/admin')]
#[ApiResource(operations: [
    new Patch(
        name: 'reset-password',
        uriTemplate: '/reset/password',
        controller: ResetPasswordController::class
    ),

    new Post(
        name: 'reset-email',
        uriTemplate: '/reset/email',
        controller: ResetEmailController::class
    ),

    new Get(
        name: 'check-token',
        uriTemplate: '/check-token/{token}',
        controller: CheckTokenController::class,
        read: false
    ),

    new Get(
        name: 'confirm-account',
        uriTemplate: '/confirm-account/{token}',
        controller: ConfirmAccountController::class,
        read: false
    )
    ], routePrefix: '/api')]
#[Patch(routePrefix: '/api')]
#[Post(routePrefix: '/api')]


])]

#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact'])]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/admin/users-host',)
])]

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['advertisement', 'owner'])]
    private ?int $id = null;


    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['advertisement', 'owner'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups('advertisement')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups('advertisement')]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('advertisement')]
    private ?string $token = null;

    #[ORM\Column(length: 255)]
    #[Groups(['advertisement', 'owner'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups('advertisement')]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups('advertisement')]
    #[Assert\LessThan('-12 years')]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('advertisement')]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Advertisement::class)]
    #[Groups('advertisement')]
    private Collection $advertisements;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Comment::class)]
    #[Groups('advertisement')]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Booking::class)]
    #[Groups('advertisement')]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Favorite::class)]
    #[Groups('advertisement')]
    private Collection $favorites;

    #[ORM\Column]
    #[Groups('advertisement')]
    private ?int $status = 0;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->advertisements = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Advertisement>
     */
    public function getAdvertisements(): Collection
    {
        return $this->advertisements;
    }

    public function addAdvertisement(Advertisement $advertisement): self
    {
        if (!$this->advertisements->contains($advertisement)) {
            $this->advertisements[] = $advertisement;
            $advertisement->setOwner($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): self
    {
        if ($this->advertisements->removeElement($advertisement)) {
            // set the owning side to null (unless already changed)
            if ($advertisement->getOwner() === $this) {
                $advertisement->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setClient($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getClient() === $this) {
                $comment->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setClient($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getClient() === $this) {
                $favorite->setClient(null);
            }
        }

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
}
