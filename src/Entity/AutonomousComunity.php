<?php

namespace App\Entity;

use App\Repository\AutonomousComunityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutonomousComunityRepository::class)]
class AutonomousComunity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, province>
     */
    #[ORM\OneToMany(targetEntity: province::class, mappedBy: 'autonomousComunity')]
    private Collection $provinces;

    public function __construct()
    {
        $this->provinces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, province>
     */
    public function getProvinces(): Collection
    {
        return $this->provinces;
    }

    public function addProvince(province $province): static
    {
        if (!$this->provinces->contains($province)) {
            $this->provinces->add($province);
            $province->setAutonomousComunity($this);
        }

        return $this;
    }

    public function removeProvince(province $province): static
    {
        if ($this->provinces->removeElement($province)) {
            // set the owning side to null (unless already changed)
            if ($province->getAutonomousComunity() === $this) {
                $province->setAutonomousComunity(null);
            }
        }

        return $this;
    }
}
