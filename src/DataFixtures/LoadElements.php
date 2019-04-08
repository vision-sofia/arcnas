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
            ['Заличени декоративни елементи', '#00FF00', 'erased_decoration'],
            ['Саниране', '#00FF00', 'sanirane'],
            ['Без саниране', '#00FF00', 'bez_sanirane'],
            ['Рушаща се фасада с декоративни елементи', '#00FF00', 'ruin_facade'],
            ['Различен цвят саниране спрямо фасада', '#00FF00', 'different_color_isolation'],
            ['Намеса в облика на фасадата', '#00FF00', 'namesa_vuv_fasadata'],
            ['Вероятно необитаемо помещение', '#00FF00', 'potential_empty_room'],
            ['Счупен прозорец', '#00FF00', 'broken_window'],
            ['Рампа за трудно подвижни хора', '#FFFF00', 'ramp'],
            ['Климатик', '#00FFFF', 'air_conditioner'],
            ['Кръгло-квадратен прозорец 0_o', '#00FF00', 'O_o_window'],
        ];
    }
}
