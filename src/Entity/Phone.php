<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $camera;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $battery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $screen;

    /**
     * @ORM\Column(type="integer", length=2, nullable=true)
     */
    private $ram;

    /**
     * @ORM\Column(type="integer", length=3, nullable=true)
     */
    private $memory;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\PrePersist
     */
    public function onAdd()
    {
        $this->setCreatedAt(new DateTime());
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new DateTime());
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

    public function getCamera(): ?string
    {
        return $this->camera;
    }

    public function setCamera(?string $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function getBattery(): ?string
    {
        return $this->battery;
    }

    public function setBattery(?string $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public function getScreen(): ?string
    {
        return $this->screen;
    }

    public function setScreen(?string $screen): self
    {
        $this->screen = $screen;

        return $this;
    }

    public function getRam(): ?string
    {
        return $this->ram;
    }

    public function setRam(?string $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    public function getMemory(): ?string
    {
        return $this->memory;
    }

    public function setMemory(?string $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getAttributes(): array
    {
        return $attributes = [
            'name' => $this->name,
            'camera' => $this->camera,
            'battery' => $this->battery,
            'screen' => $this->screen,
            'ram' => $this->ram,
            'memory' => $this->memory,
            'price' => $this->price,
            'created_at' => $this->getCreatedAt()->format('D, d M Y H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('D, d M Y H:i:s'),
        ];
    }
}
