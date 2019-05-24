<?php

namespace App\Loader;

use App\Output\PhonesOutput;
use App\Repository\PhoneRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     *
     * @return PhonesOutput|void
     *
     * @throws NonUniqueResultException
     */
    public function loadAll($page): PhonesOutput
    {
        $totalPage = $this->phoneRepository->findMaxNumberOfPage();
        $lastModified = null;

        $phones = $this->phoneRepository->findByPage($page);
        $phonesOutput = new PhonesOutput($phones, $this->urlGenerator, $page, $totalPage);

        return $phonesOutput;
    }
}
