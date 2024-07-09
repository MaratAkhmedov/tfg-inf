<?php

namespace App\Entity;

use App\Repository\AttributeFlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributeFlatRepository::class)]
class AttributeProperty extends Attribute
{
    /**
     * @var Collection<int, Property>
     */
    #[ORM\ManyToMany(targetEntity: Property::class, inversedBy: 'attributeProperties')]
    private Collection $properties;

    public function __construct()
    {
        parent::__construct();
        $this->properties = new ArrayCollection();
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): static
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
        }

        return $this;
    }

    public function removeProperty(Property $property): static
    {
        $this->properties->removeElement($property);

        return $this;
    }
}
