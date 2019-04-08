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
            ['Различен цвят саниране спрямо фасада', '#00FF00', 'different_color_san'],
            ['Намеса във фасадата', '#00FF00', 'namesa_v_fasadata'],
            ['Възможно необитаемо помещение', '#00FF00', 'potential_empty'],
            ['Счупен процорец', '#00FF00', 'broken_window'],
            ['Рампа за трудно подвижни хора', '#FFFF00', 'ramp'],
            ['Климатик', '#00FFFF', 'air_conditioner'],
            ['Кръгло-квадратен прозорец 0_o', '#00FF00', 'O_o_window'],
        ];
    }
}
