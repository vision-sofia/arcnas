<?php

namespace App\DataFixtures;


use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsers extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = new User();
        $object->setUsername('arc');
        $object->setPassword('$argon2i$v=19$m=1024,t=2,p=2$c29tZXNhbHQ$ES/eKYOOq+DhabszUX1hpIusLqiYGZUrL4PiDRiKY38'); // arc
        $object->setRoles(['ROLE_USER']);
        $object->setIsActive(true);

        $manager->persist($object);
        $manager->flush();
    }
}
