<?php

namespace App\Entity;

use App\Repository\AdministrativeAreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministrativeAreaRepository::class)]
class AdministrativeArea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $shortName = null;

    #[ORM\Column(length: 255)]
    private ?string $longName = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'administrativeAreas')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $administrativeAreas;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'administrativeArea')]
    private Collection $addresses;

    public function __construct()
    {
        $this->administrativeAreas = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getLongName(): ?string
    {
        return $this->longName;
    }

    public function setLongName(string $longName): static
    {
        $this->longName = $longName;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAdministrativeAreas(): Collection
    {
        return $this->administrativeAreas;
    }

    public function addAdministrativeArea(self $administrativeArea): static
    {
        if (!$this->administrativeAreas->contains($administrativeArea)) {
            $this->administrativeAreas->add($administrativeArea);
            $administrativeArea->setParent($this);
        }

        return $this;
    }

    public function removeAdministrativeArea(self $administrativeArea): static
    {
        if ($this->administrativeAreas->removeElement($administrativeArea)) {
            // set the owning side to null (unless already changed)
            if ($administrativeArea->getParent() === $this) {
                $administrativeArea->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setAdministrativeArea($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getAdministrativeArea() === $this) {
                $address->setAdministrativeArea(null);
            }
        }

        return $this;
    }
}
