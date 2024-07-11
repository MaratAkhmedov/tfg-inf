<?php

namespace App\Entity;

use App\Enum\BedType;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'room', cascade: ['persist'])]
    private ?Property $property = null;

    #[ORM\Column(length: 255)]
    private ?BedType $bedType = null;

    /**
     * @var Collection<int, AttributeRoom>
     */
    #[ORM\ManyToMany(targetEntity: AttributeRoom::class, mappedBy: 'rooms')]
    private Collection $attributeRooms;

    public function __construct()
    {
        $this->attributeRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): static
    {
        // unset the owning side of the relation if necessary
        if ($property === null && $this->property !== null) {
            $this->property->setRoom(null);
        }

        // set the owning side of the relation if necessary
        if ($property !== null && $property->getRoom() !== $this) {
            $property->setRoom($this);
        }

        $this->property = $property;

        return $this;
    }

    public function getBedType(): ?BedType
    {
        return $this->bedType;
    }

    public function setBedType(?BedType $bedType): static
    {
        $this->bedType = $bedType;

        return $this;
    }

    /**
     * @return Collection<int, AttributeRoom>
     */
    public function getAttributeRooms(): Collection
    {
        return $this->attributeRooms;
    }

    public function addAttributeRoom(AttributeRoom $attributeRoom): static
    {
        if (!$this->attributeRooms->contains($attributeRoom)) {
            $this->attributeRooms->add($attributeRoom);
            $attributeRoom->addRoom($this);
        }

        return $this;
    }

    public function removeAttributeRoom(AttributeRoom $attributeRoom): static
    {
        if ($this->attributeRooms->removeElement($attributeRoom)) {
            $attributeRoom->removeRoom($this);
        }

        return $this;
    }
}
