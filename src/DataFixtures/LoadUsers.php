<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsers extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        # username: demo
        # password: demo

        $object = new User();
        $object->setUsername('demo');
        $object->setPassword('$argon2i$v=19$m=1024,t=2,p=2$c29tZXNhbHQ$ulX0wpeE7xPhD0p6pOy3VxT4TPlnaIDYdHwYMGGedYs');
        $object->addRole('ROLE_USER');
        $object->setIsActive(true);

        $manager->persist($object);
        $manager->flush();
    }
}
