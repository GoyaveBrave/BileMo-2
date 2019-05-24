<?php

namespace App\Output;

use App\Entity\Customer;
use App\Output\Interfaces\CustomersOutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomersOutput implements CustomersOutputInterface
{
    private $page;
    private $links;
    private $data = [];

    /**
     * PhonesOutput constructor.
     *
     * @param array                 $customers
     * @param UrlGeneratorInterface $urlGenerator
     * @param null                  $page
     * @param null                  $totalPage
     */
    public function __construct(array $customers, UrlGeneratorInterface $urlGenerator, $page = null, $totalPage = null)
    {
        $this->links = [
            'self' => $urlGenerator->generate('customer_list', [], 0),
            'create' => $urlGenerator->generate('customer_creation', [], 0),
        ];

        if ($page && $totalPage) {
            $this->page = $page.'/'.$totalPage;
        }

        /* @var Customer $customer */
        foreach ($customers as $customer) {
            $this->data[] = new Output('customer', $customer->getId(), $customer->getAttributes());
        }
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
