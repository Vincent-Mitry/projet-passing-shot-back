<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CourtRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CourtRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity(fields={"name"})
 */
class Court
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"court_list"}) 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     * )
     * 
     * @Groups({"reservations_get_item"})
     * @Groups ({"court_list"}) 
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Choice(choices = {true, false})
     * 
     * @Groups ({"court_list"}) 
     */
    private $lightning;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Choice(choices = {true, false})
     * 
     * @Groups ({"court_list"}) 
     */
    private $indoor;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Veuillez ajouter un horaire d'ouverture pour le terrain")
     * @Assert\Type("\DateTimeInterface")
     * 
     * @Groups ({"court_list"}) 
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Veuillez ajouter un horaire de fermeture pour le terrain")
     * @Assert\Type("\DateTimeInterface")
     * 
     * @Groups({"court_list"}) 
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"court_list"}) 
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"court_list"}) 
     */
    private $detailed_map;

    /**
     * @ORM\Column(type="decimal", precision=2, scale=1, nullable=true)
     * @Groups({"court_list"}) 
     */
    private $ratingAvg;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     * )
     * 
     * @Groups({"court_list"}) 
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type("\DateTimeInterface")
     * 
     * @Groups({"court_list"}) 
     */
    private $renovatedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type("\DateTimeInterface")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="court")
     * @Ignore()
     */
    private $reservations;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="courts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez associer un club au terrain")
     * 
     * @Ignore()
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity=BlockedCourt::class, mappedBy="court")
     */
    private $blockedCourts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $RatingCount;

    /**
     * @ORM\ManyToOne(targetEntity=Surface::class, inversedBy="courts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "Veuillez sélectionner un terrain")
     * 
     * @Groups ({"court_list"})
     */
    private $surface;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->blockedCourts = new ArrayCollection();
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

    public function isLightning(): ?bool
    {
        return $this->lightning;
    }

    public function setLightning(bool $lightning): self
    {
        $this->lightning = $lightning;

        return $this;
    }

    public function isIndoor(): ?bool
    {
        return $this->indoor;
    }

    public function setIndoor(bool $indoor): self
    {
        $this->indoor = $indoor;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDetailedMap(): ?string
    {
        return $this->detailed_map;
    }

    public function setDetailedMap(string $detailed_map): self
    {
        $this->detailed_map = $detailed_map;

        return $this;
    }

    public function getRatingAvg(): ?string
    {
        return $this->ratingAvg;
    }

    public function setRatingAvg(?string $ratingAvg): self
    {
        $this->ratingAvg = $ratingAvg;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getRenovatedAt(): ?\DateTimeImmutable
    {
        return $this->renovatedAt;
    }

    public function setRenovatedAt(?\DateTimeImmutable $renovatedAt): self
    {
        $this->renovatedAt = $renovatedAt;

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
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setCourt($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCourt() === $this) {
                $reservation->setCourt(null);
            }
        }

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    /**
     * @return Collection<int, BlockedCourt>
     */
    public function getBlockedCourts(): Collection
    {
        return $this->blockedCourts;
    }

    public function addBlockedCourt(BlockedCourt $blockedCourt): self
    {
        if (!$this->blockedCourts->contains($blockedCourt)) {
            $this->blockedCourts[] = $blockedCourt;
            $blockedCourt->setCourt($this);
        }

        return $this;
    }

    public function removeBlockedCourt(BlockedCourt $blockedCourt): self
    {
        if ($this->blockedCourts->removeElement($blockedCourt)) {
            // set the owning side to null (unless already changed)
            if ($blockedCourt->getCourt() === $this) {
                $blockedCourt->setCourt(null);
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

    public function getRatingCount(): ?int
    {
        return $this->RatingCount;
    }

    public function setRatingCount(?int $RatingCount): self
    {
        $this->RatingCount = $RatingCount;

        return $this;
    }

    public function getSurface(): ?Surface
    {
        return $this->surface;
    }

    public function setSurface(?Surface $surface): self
    {
        $this->surface = $surface;

        return $this;
    }
}
