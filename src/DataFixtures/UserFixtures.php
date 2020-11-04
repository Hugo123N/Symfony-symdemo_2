<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// add to encoder
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 4; $i++) { 
            $user = new User();
            $array = [
                'ROLE_USER',
                'ROLE_ADMIN'
            ];
            $user->setName('vanteo'.$i);
            $user->setEmail('adc'.$i.'@gmail.com');
            $user->setRoles( array_slice($array, 0, mt_rand(1, 2)) );
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '12345'
            ));
            $manager->persist($user);
            $manager->flush();
            }
    }
}
