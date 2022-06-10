<?php

namespace App\Entity;

use App\Repository\CourtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourtRepository::class)
 */
class Court
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $surface;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lightning;

    /**
     * @ORM\Column(type="boolean")
     */
    private $type;

    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $detailled_map;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2, nullable=true)
     */
    private $ratingAvg;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $renovatedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="court")
     */
    private $reservations;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="courts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity=BlockedCourt::class, mappedBy="court")
     */
    private $blockedCourts;

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

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

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

    public function isType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

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

    public function getDetailledMap(): ?string
    {
        return $this->detailled_map;
    }

    public function setDetailledMap(string $detailled_map): self
    {
        $this->detailled_map = $detailled_map;

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
}
