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
        $user1 = new User('SFR', 'password');
        $user2 = new User('Orange', 'password');
        $manager->persist($user1);
        $manager->persist($user2);

        //SFR customers
        for ($i = 0; $i < 10; ++$i) {
            $number = $i + 1;
            $customer = new Customer(
                'customer '.$number,
                'Doe',
                'email'.$number.'@gmail.com',
                $user1
            );
            $manager->persist($customer);
        }

        //Orange customers
        for ($i = 10; $i < 20; ++$i) {
            $number = $i + 1;
            $customer = new Customer(
                'customer '.$number,
                'Doe',
                'email'.$number.'@gmail.com',
                $user2
            );
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
