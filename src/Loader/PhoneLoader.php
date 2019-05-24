<?php

namespace App\Loader;

use App\Entity\Phone;
use App\Output\PhoneOutput;
use App\Output\PhonesOutput;
use App\Repository\PhoneRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PhoneLoader
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * PhoneLoader constructor.
     *
     * @param PhoneRepository       $phoneRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(PhoneRepository $phoneRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->phoneRepository = $phoneRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param $page
     * @param $totalPage
     *
     * @return PhonesOutput|void
     */
    public function loadAll($page, $totalPage): PhonesOutput
    {
        $phones = $this->phoneRepository->findByPage($page);
        $phonesOutput = new PhonesOutput($phones, $this->urlGenerator, $page, $totalPage);

        return $phonesOutput;
    }

    public function load(Phone $phone)
    {
        $phoneOutput = new PhoneOutput($phone, $this->urlGenerator);

        return $phoneOutput;
    }
}
