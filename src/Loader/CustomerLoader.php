<?php

namespace App\Loader;

use App\Entity\Customer;
use App\Output\CustomerOutput;
use App\Output\CustomersOutput;
use App\Repository\CustomerRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerLoader
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * CustomerLoader constructor.
     *
     * @param CustomerRepository    $customerRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(CustomerRepository $customerRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->customerRepository = $customerRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param $user
     * @param $page
     * @param $totalPage
     *
     * @param $maxResult
     * @return CustomersOutput
     */
    public function loadAll(UserInterface $user, int $page, int $totalPage, int $maxResult): CustomersOutput
    {
        $customers = $this->customerRepository->findByPage($page, $maxResult, $user);
        $customersOutput = new CustomersOutput($customers, $this->urlGenerator, $page, $totalPage);

        return $customersOutput;
    }

    public function load(Customer $customer)
    {
        $customerOutput = new CustomerOutput($customer, $this->urlGenerator);

        return $customerOutput;
    }
}
