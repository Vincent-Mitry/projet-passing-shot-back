<?php

namespace App\Entity;

use App\Repository\BlockedCourtRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BlockedCourtRepository::class)
 * @UniqueEntity(fields={"startDatetime", "endDatetime"})
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class BlockedCourt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThanOrEqual(
     *  "today",
     *  message = "La date choisie ne peut pas être inférieure à la date d'aujourd'hui."
     * )
     */
    private $startDatetime;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThanOrEqual(
     *  "today",
     *  message = "La date choisie ne peut pas être inférieure à la date d'aujourd'hui."
     * )
     * @Assert\GreaterThan(
     *  propertyPath = "startDatetime",
     *  message = "La date et heure de fin doit être supérieure à celle du début."
     * )
     */
    private $endDatetime;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message = "Veuillez indiquer la raison du blocage du terrain.")
     * @Assert\Length(
     *      min = 2,
     *      max = 200,
     * )
     */
    private $blockedReason;

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
     * @ORM\ManyToOne(targetEntity=Court::class, inversedBy="blockedCourts")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @Assert\NotNull(message = "Veuillez associer le terrain bloqué.")
     */
    private $court;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blockedCourts")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(?\DateTimeInterface $startDatetime): self
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTimeInterface
    {
        return $this->endDatetime;
    }

    public function setEndDatetime(?\DateTimeInterface $endDatetime): self
    {
        $this->endDatetime = $endDatetime;

        return $this;
    }

    public function getBlockedReason(): ?string
    {
        return $this->blockedReason;
    }

    public function setBlockedReason(?string $blockedReason): self
    {
        $this->blockedReason = $blockedReason;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
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

    public function getCourt(): ?Court
    {
        return $this->court;
    }

    public function setCourt(?Court $court): self
    {
        $this->court = $court;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
