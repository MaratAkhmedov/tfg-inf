<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[HasLifecycleCallbacks]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $numBathrooms = null;

    #[ORM\Column(nullable: true)]
    private ?int $numRooms = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxPersons = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lastFloor = null;

    #[ORM\Column(nullable: true)]
    private ?int $floor = null;

    #[ORM\Column(nullable: true)]
    private ?int $square = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTimeInterface $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?User $user = null;

    /**
     * @var Collection<int, Rule>
     */
    #[ORM\ManyToMany(targetEntity: Rule::class, inversedBy: 'properties')]
    private Collection $rules;

    #[ORM\OneToOne(inversedBy: 'property', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Address $address = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'property', cascade: ['persist', 'remove'])]
    private Collection $photos;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PropertyType $type = null;

    /**
     * @var Collection<int, AttributeProperty>
     */
    #[ORM\ManyToMany(targetEntity: AttributeProperty::class, mappedBy: 'properties')]
    private Collection $attributeProperties;

    #[ORM\OneToOne(inversedBy: 'property', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?Room $room = null;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'property', cascade: ['persist'], orphanRemoval: true)]
    private Collection $favorites;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->attributeProperties = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getNumBathrooms(): ?int
    {
        return $this->numBathrooms;
    }

    public function setNumBathrooms(?int $numBathrooms): static
    {
        $this->numBathrooms = $numBathrooms;

        return $this;
    }

    public function getNumRooms(): ?int
    {
        return $this->numRooms;
    }

    public function setNumRooms(?int $numRooms): static
    {
        $this->numRooms = $numRooms;

        return $this;
    }

    public function getMaxPersons(): ?int
    {
        return $this->maxPersons;
    }

    public function setMaxPersons(?int $maxPersons): static
    {
        $this->maxPersons = $maxPersons;

        return $this;
    }

    public function islastFloor(): ?bool
    {
        return $this->lastFloor;
    }

    public function setlastFloor(?bool $lastFloor): static
    {
        $this->lastFloor = $lastFloor;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): static
    {
        $this->floor = $floor;

        return $this;
    }

    public function getSquare(): ?int
    {
        return $this->square;
    }

    public function setSquare(?int $square): static
    {
        $this->square = $square;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Rule>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Rule $rule): static
    {
        if (!$this->rules->contains($rule)) {
            $this->rules->add($rule);
            $rule->addProperty($this);
        }

        return $this;
    }

    public function removeRule(Rule $rule): static
    {
        if ($this->rules->removeElement($rule)) {
            $rule->removeProperty($this);
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProperty($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProperty() === $this) {
                $photo->setProperty(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the value of type
     */ 
    public function getType(): ?PropertyType
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType(?PropertyType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, AttributeProperty>
     */
    public function getAttributeProperties(): Collection
    {
        return $this->attributeProperties;
    }

    public function addAttributeProperty(AttributeProperty $attributeProperty): static
    {
        if (!$this->attributeProperties->contains($attributeProperty)) {
            $this->attributeProperties->add($attributeProperty);
            $attributeProperty->addProperty($this);
        }

        return $this;
    }

    public function removeAttributeProperty(AttributeProperty $attributeProperty): static
    {
        if ($this->attributeProperties->removeElement($attributeProperty)) {
            $attributeProperty->removeProperty($this);
        }

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setProperty($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getProperty() === $this) {
                $favorite->setProperty(null);
            }
        }

        return $this;
    }
}
