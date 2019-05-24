<?php

namespace App\Output;

use App\Entity\Phone;
use App\Output\Interfaces\PhonesOutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PhonesOutput implements PhonesOutputInterface
{
    private $page;
    private $links;
    private $data = [];

    /**
     * PhonesOutput constructor.
     *
     * @param array                 $phones
     * @param UrlGeneratorInterface $urlGenerator
     * @param null                  $page
     * @param null                  $totalPage
     */
    public function __construct(array $phones, UrlGeneratorInterface $urlGenerator, $page = null, $totalPage = null)
    {
        $this->links = ['self' => $urlGenerator->generate('phone_list', [], 0)];
        if ($page && $totalPage) {
            $this->page = $page.'/'.$totalPage;
        }

        /** @var Phone $phone */
        foreach ($phones as $phone) {
            $this->data[] = new Output('phone', $phone->getId(), $phone->getAttributes());
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
