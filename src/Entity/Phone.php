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
     * Phone constructor.
     * @param $name
     * @param $camera
     * @param $battery
     * @param $screen
     * @param $ram
     * @param $memory
     * @param $price
     */
    public function __construct($name, $price, $camera = null, $battery = null, $screen = null, $ram = null, $memory = null)
    {
        $this->name = $name;
        $this->camera = $camera;
        $this->battery = $battery;
        $this->screen = $screen;
        $this->ram = $ram;
        $this->memory = $memory;
        $this->price = $price;
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

    public function getName(): ?string
    {
        return $this->name;
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
