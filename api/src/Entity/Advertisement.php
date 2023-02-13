<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\SearchController;
use App\Repository\AdvertisementRepository;
use App\Controller\SearchAdvertisementController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// #[ApiResource]

#[ApiResource(normalizationContext: ['groups' => ['owner', 'bookings']], routePrefix: '/api')]
#[ApiFilter(SearchFilter::class, properties: ['city' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['date_start', 'date_end'])]
#[ApiResource(operations: [
    new GetCollection(
        name: 'advertisements',
        uriTemplate: '/advertisements',
        normalizationContext: ['groups' => ['owner']],
    ),

    // new Get(
    //     name: 'search-advertisements',
    //     uriTemplate: '/search-advertisements',
    //     controller: SearchController::class
    // )
])]

#[Get()]

#[ApiResource(operations: [
    new GetCollection(
        name: 'advertisements',
        uriTemplate: '/advertisements',
        normalizationContext: ['groups' => ['owner']],
    ),

    new Get(
        name: 'search-advertisements',
        uriTemplate: '/search-advertisements',
        controller: SearchController::class
    )
])]

#[ORM\Entity(repositoryClass: AdvertisementRepository::class)]
#[ORM\Table(name: '`advertisement`')]
class Advertisement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['booking:read', 'advertisement', 'owner', 'bookings'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private array $photo = [];
    

    #[ORM\Column(nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private array $properties = [];

    #[ORM\ManyToOne(inversedBy: 'advertisements')]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'advertisement', targetEntity: Comment::class)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'advertisement', targetEntity: Booking::class)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private Collection $bookings;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    #[Assert\Length(max: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?string $address = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Assert\Length(max: 5)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?string $zipcode = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column]
    #[Groups(['owner', 'bookings'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['advertisement', 'owner', 'bookings'])]
    private ?bool $status = true;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): array
    {
        return $this->photo;
    }

    public function setPhoto(?array $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(?array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
            $comment->setAdvertisement($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAdvertisement() === $this) {
                $comment->setAdvertisement(null);
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
            $booking->setAdvertisement($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAdvertisement() === $this) {
                $booking->setAdvertisement(null);
            }
        }

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        if (preg_match("~^[0-9]{5}$~", $zipcode)) {
            $this->zipcode = $zipcode;
        } else {
            $this->zipcode = "00000";
        }
        return $this;
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

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
