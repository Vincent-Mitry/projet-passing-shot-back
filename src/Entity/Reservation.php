<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity(fields={"startDatetime", "court"})
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     * @Groups({"user_see_reservations"})
     * @Groups({"past_user_reservations"})
     */
    private $startDatetime;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     * @Groups({"user_see_reservations"})
     * @Groups({"past_user_reservations"})
     */
    private $endDatetime;

    /**
     * @ORM\Column(type="smallint", options={"default" : 1})
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=19, nullable=true)
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     * @Groups({"past_user_reservations"})
     */
    private $score;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\LessThan(6)
     * @Assert\GreaterThan(0)
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     * @Groups({"past_user_reservations"})
     */
    private $courtRating;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Assert\Choice(choices = {2, 3, 4})
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     * @Groups({"past_user_reservations"})
     */
    private $countPlayers;

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
     * @ORM\ManyToOne(targetEntity=Court::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     */
    private $court;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $player2;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $player3;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $player4;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDatetime(): ?\DateTimeImmutable
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(\DateTimeImmutable $startDatetime): self
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTimeImmutable
    {
        return $this->endDatetime;
    }

    public function setEndDatetime(\DateTimeImmutable $endDatetime): self
    {
        $this->endDatetime = $endDatetime;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(?string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getCourtRating(): ?int
    {
        return $this->courtRating;
    }

    public function setCourtRating(?int $courtRating): self
    {
        $this->courtRating = $courtRating;

        return $this;
    }

    public function getCountPlayers(): ?int
    {
        return $this->countPlayers;
    }

    public function setCountPlayers(int $countPlayers): self
    {
        $this->countPlayers = $countPlayers;

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

    public function getPlayer2(): ?User
    {
        return $this->player2;
    }

    public function setPlayer2(?User $player2): self
    {
        $this->player2 = $player2;

        return $this;
    }

    public function getPlayer3(): ?User
    {
        return $this->player3;
    }

    public function setPlayer3(?User $player3): self
    {
        $this->player3 = $player3;

        return $this;
    }

    public function getPlayer4(): ?User
    {
        return $this->player4;
    }

    public function setPlayer4(?User $player4): self
    {
        $this->player4 = $player4;

        return $this;
    }
}
