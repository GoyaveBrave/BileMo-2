<?php

namespace App\Output;

class Output
{
    private $type;
    private $id;
    private $attributes;

    public function __construct(string $type, int $id, array $attributes)
    {
        $this->type = $type;
        $this->id = $id;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
