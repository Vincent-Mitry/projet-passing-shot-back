<?php

namespace App\Entity;

use App\Entity\Club;
use App\Entity\Reservation;
use App\Entity\BlockedCourt;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * 
     * @Groups({"reservations_get_item"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank
     * @Assert\Email
     * 
     * @Groups({"reservations_get_item"})
     */
    private $email;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Assert\Choice(choices = {1, 2, 3})
     */
    private $gender;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * @Assert\Choice(choices = {1, 2, 3})
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="json")
     * @Assert\Choice({"ROLE_MEMBER", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"}, multiple=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\DateTime
     * @Assert\NotBlank
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="user")
     */
    private $reservations;

    /**
     * @ORM\OneToOne(targetEntity=Club::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity=BlockedCourt::class, mappedBy="user")
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

     /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeImmutable
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeImmutable $birthdate): self
    {
        $this->birthdate = $birthdate;

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
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(Club $club): self
    {
        // set the owning side of the relation if necessary
        if ($club->getUser() !== $this) {
            $club->setUser($this);
        }

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
            $blockedCourt->setUser($this);
        }

        return $this;
    }

    public function removeBlockedCourt(BlockedCourt $blockedCourt): self
    {
        if ($this->blockedCourts->removeElement($blockedCourt)) {
            // set the owning side to null (unless already changed)
            if ($blockedCourt->getUser() === $this) {
                $blockedCourt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
