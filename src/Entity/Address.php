<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist'])]
    private ?Property $property = null;

    #[ORM\ManyToOne(inversedBy: 'addresses', cascade:['persist'])]
    private ?City $city = null;

    //#[ORM\ManyToOne(inversedBy: 'addresses')]
    //private ?AdministrativeArea $administrativeArea = null;

    #[ORM\Column(length: 255)]
    private ?string $formattedAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $placeId = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): static
    {
        // set the owning side of the relation if necessary
        if ($property->getAddress() !== $this) {
            $property->setAddress($this);
        }

        $this->property = $property;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;
        return $this;
    }

    // public function getAdministrativeArea(): ?AdministrativeArea
    // {
    //     return $this->administrativeArea;
    // }

    // public function setAdministrativeArea(?AdministrativeArea $administrativeArea): static
    // {
    //     $this->administrativeArea = $administrativeArea;

    //     return $this;
    // }

    public function getFormattedAddress(): ?string
    {
        return $this->formattedAddress;
    }

    public function setFormattedAddress(string $formattedAddress): static
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    public function getPlaceId(): ?string
    {
        return $this->placeId;
    }

    public function setPlaceId(string $placeId): static
    {
        $this->placeId = $placeId;

        return $this;
    }
}
