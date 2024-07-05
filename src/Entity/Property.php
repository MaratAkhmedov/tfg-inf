<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
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
    private ?bool $lastPlant = null;

    #[ORM\Column(nullable: true)]
    private ?int $floor = null;

    #[ORM\Column(nullable: true)]
    private ?int $square = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?User $user = null;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\ManyToMany(targetEntity: Equipment::class, mappedBy: 'properties')]
    private Collection $equipments;

    /**
     * @var Collection<int, Rule>
     */
    #[ORM\ManyToMany(targetEntity: Rule::class, mappedBy: 'properties')]
    private Collection $rules;

    #[ORM\OneToOne(inversedBy: 'property', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Address $address = null;

    /**
     * @var Collection<int, File>
     */
    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'property')]
    private Collection $files;

    public function __construct()
    {
        $this->equipments = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->files = new ArrayCollection();
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

    public function isLastPlant(): ?bool
    {
        return $this->lastPlant;
    }

    public function setLastPlant(?bool $lastPlant): static
    {
        $this->lastPlant = $lastPlant;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

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
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
            $equipment->addProperty($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->equipments->removeElement($equipment)) {
            $equipment->removeProperty($this);
        }

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
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setProperty($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProperty() === $this) {
                $file->setProperty(null);
            }
        }

        return $this;
    }
}
