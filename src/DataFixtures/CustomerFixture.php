<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User('SFR', 'password');
        $manager->persist($user);

        for ($i = 0; $i < 20; ++$i) {
            $customer = new Customer(
                'customer '.$i,
                'Doe',
                'email'.$i.'@gmail.com',
                $user
            );
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
