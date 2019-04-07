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
            ['Улук', '#FFFF00', '_empty'],
            ['Фонтон', '#FFFF00', '_empty'],
            ['Корниз', '#FFFF00', '_empty'],
            ['Кронщайн', '#FFFF00', '_empty'],
            ['Балкон неостъклен ', '#00FF00', '_empty'],
            ['Балкон остъклен ', '#00FF00', '_empty'],
            ['Фасада', '#FF0000', '_empty'],
            ['Климатик', '#00FF00', '_empty'],
            ['Антена', '#00FF00', '_empty'],
            ['Колона', '#00FF00', '_empty'],
            ['Вход', '#00FF00', '_empty'],
            ['Прозорец', '#00FF00', '_empty'],
            ['Саниране', '#00FF00', '_empty'],
            ['Кабели', '#00FF00', '_empty'],
            ['Бизнес', '#00FF00', '_empty'],
            ['Графити', '#00FF00', '_empty'],
            ['Витрини', '#00FF00', '_empty'],
            ['Строителна мрежа', '#00FF00', '_empty'],
            ['Гараж', '#00FF00', '_empty'],
            ['Велосипед на балкон', '#00FF00', '_empty'],
            ['Пране', '#00FF00', '_empty'],
            ['Саксии', '#00FF00', '_empty'],
            ['Знаме', '#00FF00', '_empty'],
        ];
    }
}
