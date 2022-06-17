<?php

namespace App\Entity;

use App\Repository\SurfaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SurfaceRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class Surface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\NotBlank(message="Veuillez renseigner le nom de la surface")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * 
     * @Groups ({"court_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Court::class, mappedBy="surface")
     */
    private $courts;

    public function __construct()
    {
        $this->courts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Court>
     */
    public function getCourts(): Collection
    {
        return $this->courts;
    }

    public function addCourt(Court $court): self
    {
        if (!$this->courts->contains($court)) {
            $this->courts[] = $court;
            $court->setSurface($this);
        }

        return $this;
    }

    public function removeCourt(Court $court): self
    {
        if ($this->courts->removeElement($court)) {
            // set the owning side to null (unless already changed)
            if ($court->getSurface() === $this) {
                $court->setSurface(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
