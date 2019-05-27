<?php

namespace App\DTO;

class PhoneDTO
{
    private $name;

    private $camera;

    private $battery;

    private $screen;

    private $ram;

    private $memory;

    private $price;

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $camera
     */
    public function setCamera($camera): void
    {
        $this->camera = $camera;
    }

    /**
     * @param mixed $battery
     */
    public function setBattery($battery): void
    {
        $this->battery = $battery;
    }

    /**
     * @param mixed $screen
     */
    public function setScreen($screen): void
    {
        $this->screen = $screen;
    }

    /**
     * @param mixed $ram
     */
    public function setRam($ram): void
    {
        $this->ram = $ram;
    }

    /**
     * @param mixed $memory
     */
    public function setMemory($memory): void
    {
        $this->memory = $memory;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCamera()
    {
        return $this->camera;
    }

    /**
     * @return mixed
     */
    public function getBattery()
    {
        return $this->battery;
    }

    /**
     * @return mixed
     */
    public function getScreen()
    {
        return $this->screen;
    }

    /**
     * @return mixed
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * @return mixed
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }


}
