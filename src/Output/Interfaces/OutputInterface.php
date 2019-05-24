<?php

namespace App\Output\Interfaces;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface OutputInterface
{
    public function __construct(array $entities, UrlGeneratorInterface $urlGenerator, $pages = null, $totalPage = null);
    public function getLinks(): array;
    public function getData(): array;
}
