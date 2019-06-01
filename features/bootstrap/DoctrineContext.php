<?php

use App\DataFixtures\CustomerFixture;
use App\DataFixtures\PhoneFixture;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineContext implements Context
{
    private $entityManager;
    private $container;

    /**
     * DoctrineContext constructor.
     *
     * @param ContainerInterface     $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     *
     * @throws ToolsException
     */
    public function initDatabase()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $this->loadFixtures();
    }

    protected function loadFixtures()
    {
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);

        $customerDataFixtures = new CustomerFixture();
        $phoneDataFixtures = new PhoneFixture();
        $loader = new Loader();
        $loader->addFixture($customerDataFixtures);
        $loader->addFixture($phoneDataFixtures);

        $executor->execute($loader->getFixtures());
    }
}
