<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadUsers extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $object = new User();
        $object->setUsername('demo');
        $object->setPassword($this->passwordEncoder->encodePassword($object, 'demo'));
        $object->addRole('ROLE_USER');
        $object->setIsActive(true);

        $manager->persist($object);
        $manager->flush();
    }
}
