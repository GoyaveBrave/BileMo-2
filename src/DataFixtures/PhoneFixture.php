<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PhoneFixture extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; ++$i) {
            $phone = new Phone('phone '.$i, mt_rand(50, 800));
            $manager->persist($phone);
        }

        $manager->flush();
    }
}
