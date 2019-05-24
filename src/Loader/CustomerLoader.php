<?php

namespace App\Loader;

use App\Output\CustomersOutput;
use App\Repository\CustomerRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @return CustomersOutput
     */
    public function loadAll($user, $page, $totalPage): CustomersOutput
    {
        $customers = $this->customerRepository->findByPage($page, $user);
        $customersOutput = new CustomersOutput($customers, $this->urlGenerator, $page, $totalPage);

        return $customersOutput;
    }
}
