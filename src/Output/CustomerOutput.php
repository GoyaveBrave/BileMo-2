<?php

namespace App\Output;

use App\Entity\Customer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerOutput
{
    private $data;
    private $links;

    /**
     * CustomerOutput constructor.
     *
     * @param Customer              $customer
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Customer $customer, UrlGeneratorInterface $urlGenerator)
    {
        $this->links = [
            'self' => $urlGenerator->generate('customer_show', ['id' => $customer->getId()], 0),
            'list' => $urlGenerator->generate('customer_list', [], 0),
            'create' => $urlGenerator->generate('customer_creation', [], 0),
            'delete' => $urlGenerator->generate('customer_delete', ['id' => $customer->getId()], 0),
        ];

        $this->data = new Output('customer', $customer->getId(), $customer->getAttributes());
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
