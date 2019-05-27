<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('SFR');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $user->setRoles(['ROLE_USER']);
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
