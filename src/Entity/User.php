<?php

namespace App\Entity;

use App\Entity\Club;
use App\Entity\Reservation;
use App\Entity\BlockedCourt;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"user_list", "user_detail", "user_list_search_by_lastname"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message = "Veuillez renseigner le nom de l'utilisateur.")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     * )
     *  
     * @Groups({"reservations_get_item"})
     * @Groups ({"user_list", "user_detail", "user_update", "user_list_search_by_lastname"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message = "Veuillez renseigner le prénom de l'utilisateur.")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     * )
     *  
     * @Groups({"reservations_get_item"})
     * @Groups ({"user_list", "user_detail", "user_update", "user_list_search_by_lastname"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank(message = "Veuillez renseigner l'adresse mail de l'utilisateur.")
     * @Assert\Email(message = "{{ value }} n'est pas une adresse mail valide.")
     * @Assert\Length(
     *      min = 2,
     *      max = 180,
     * )
     * 
     * @Groups({"reservations_get_item"})
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message = "Veuillez sélectionner votre niveau.")
     * @Assert\Choice(choices = {"Débutant", "Intermédiaire", "Confirmé"})
     * 
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message = "Veuillez renseigner votre numéro de téléphone.")
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      exactMessage = "Le numéro de téléphone doit se comporter de 10 chiffres (format français sans indicatif)."
     * )
     * 
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $phone;

    /**

     * @var string The hashed password
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max = 100)
     * @Assert\NotBlank(message = "Veuillez saisir un mot de passe.")
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $picture;

    /**
     * @ORM\Column(type="json")
     * @Assert\Choice({"ROLE_MEMBER", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"}, multiple=true)
     * 
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $roles = [];

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
     * @ORM\Column(type="datetime_immutable")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\NotNull(message = "Veuillez ajouter votre date de naissance.")
     * 
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="user")
     * @Ignore()
     */
    private $reservations;

    /**
     * @ORM\OneToOne(targetEntity=Club::class, mappedBy="user")
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity=BlockedCourt::class, mappedBy="user")
     */
    private $blockedCourts;

    /**
     * @ORM\ManyToOne(targetEntity=Gender::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message = "Veuillez sélectionner un genre.")
     * 
     * @Groups ({"user_list", "user_detail", "user_update"})
     */
    private $gender;

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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
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

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}