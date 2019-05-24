<?php

namespace App\Output;

use App\Entity\Phone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PhoneOutput
{
    private $data;
    private $links;

    public function __construct(Phone $phone, UrlGeneratorInterface $urlGenerator)
    {
        $this->links = [
            'self' => $urlGenerator->generate('phone_show', ['id' => $phone->getId()], 0),
            'list' => $urlGenerator->generate('phone_list', [], 0),
            'create' => $urlGenerator->generate('phone_creation', [], 0),
            'delete' => $urlGenerator->generate('phone_delete', ['id' => $phone->getId()], 0),
        ];

        $this->data = new Output('phone', $phone->getId(), $phone->getAttributes());
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getData(): Output
    {
        return $this->data;
    }
}
