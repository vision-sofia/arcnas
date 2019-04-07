<?php

namespace App\DataFixtures;


use App\Entity\ConfigurationList\Element;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadElements extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->data() as $item) {
            $object = new Element();
            $object->setName($item[0]);
            $object->setPrimaryColor($item[1]);
            $object->setAttributeName($item[2]);

            $manager->persist($object);
        }

        $manager->flush();
    }

    private function data(): array
    {
        return [
            ['Счупен процорец', '#00FF00', '_broken_window'],
            ['Рампа', '#FFFF00', '_ramp'],
            ['Климатик', '#00FFFF', '_air_conditioner'],
            ['Графити', '#FF00FF', '_graffiti'],
        ];
    }
}
