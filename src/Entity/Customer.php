<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"display"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"display", "insert"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"display", "insert"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"display", "insert"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * Customer constructor.
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param $user
     */
    public function __construct($firstname, $lastname, $email, $user)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->user = $user;
    }

    /**
     * @ORM\PrePersist
     */
    public function onAdd()
    {
        $this->created_at = new DateTime();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updated_at = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function getAttributes(): array
    {
        return $attributes = [
            'first_name' => $this->firstname,
            'last_name' => $this->lastname,
            'email' => $this->email,
            'created_at' => $this->getCreatedAt()->format('D, d M Y H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('D, d M Y H:i:s'),
        ];
    }
}
