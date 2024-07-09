<?php

namespace App\Entity;

use App\Repository\AttributeRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributeRoomRepository::class)]
class AttributeRoom extends Attribute
{
    /**
     * @var Collection<int, Room>
     */
    #[ORM\ManyToMany(targetEntity: Room::class, inversedBy: 'attributeRooms')]
    private Collection $rooms;

    public function __construct()
    {
        parent::__construct();
        $this->rooms = new ArrayCollection();
    }

    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): static
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
        }

        return $this;
    }

    public function removeRoom(Room $room): static
    {
        $this->rooms->removeElement($room);

        return $this;
    }
}
