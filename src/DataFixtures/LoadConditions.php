<?php

namespace App\DataFixtures;


use App\Entity\ConfigurationList\Condition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadConditions extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->data() as $item) {
            $object = new Condition();
            $object->setName($item);

            $manager->persist($object);
        }

        $manager->flush();
    }

    private function data(): array
    {
        return [
            'Лошо',
            'Задоволително',
            'Добро',
            'Отлично',
        ];
    }
}
