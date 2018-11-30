<?php

namespace App\DataFixtures;


use App\Entity\Element;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadElements extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->data() as $item) {
            $object = new Element();
            $object->setName($item);

            $manager->persist($object);
        }

        $manager->flush();
    }

    private function data(): array
    {
        return [
            'Улук',
            'Фриз',
            'Фонтон',
            'Корниз',
            'Кронщайн',
        ];
    }
}
