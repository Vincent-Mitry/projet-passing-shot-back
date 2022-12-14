<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"contact_detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups ({"contact_detail"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups ({"contact_detail"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups ({"contact_detail"})
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Groups ({"contact_detail"})
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
