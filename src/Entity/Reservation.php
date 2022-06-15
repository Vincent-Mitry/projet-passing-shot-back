<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
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
     */
    private $score;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\LessThan(6)
     * @Assert\GreaterThan(0)
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     */
    private $courtRating;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Assert\Choice(choices = {2, 3, 4})
     * 
     * @Groups({"reservations_get_item"})
     * @Groups({"reservations_put_item"})
     * @Groups({"user_see_reservations"})
     */
    private $countPlayers;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     * @Groups({"reservations_put_item"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type("\DateTimeInterface")
     * @Groups({"reservations_put_item"})
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
}
