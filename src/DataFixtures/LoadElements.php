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

            $manager->persist($object);
        }

        $manager->flush();
    }

    private function data(): array
    {
        return [
            ['Улук', '#FFFF00'],
            ['Фонтон', '#FFFF00'],
            ['Корниз', '#FFFF00'],
            ['Кронщайн', '#FFFF00'],
            ['Балкон неостъклен ', '#0000FF'],
            ['Балкон остъклен ', '#F000F'],
            ['Фасада', '#FF0000'],
            ['Климатик', '#00FF00'],
            ['Колона', '#00FF00'],
            ['Врата', '#00FF00'],
            ['Прозорец', '#00FF00'],
            ['Саниране', '#00FF00'],
        ];
    }
}
