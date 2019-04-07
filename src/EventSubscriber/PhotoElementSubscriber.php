<?php

namespace App\EventSubscriber;


use App\Event\Events;
use App\Event\PhotoElementTouchEvent;
use App\Services\Database\PhotoElement;
use App\Services\WorldObject\Attribute;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PhotoElementSubscriber implements EventSubscriberInterface
{
    protected $photoElement;
    protected $worldObjectAttribute;

    public function __construct(
        PhotoElement $photoElement,
        Attribute $worldObjectAttribute
    ) {
        $this->photoElement = $photoElement;
        $this->worldObjectAttribute = $worldObjectAttribute;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::PHOTO_ELEMENT_INSERT => [
                ['updateSector', 70],
                ['updateWorldObjectAttribute', 60],
            ],
        ];
    }

    public function updateSector(PhotoElementTouchEvent $event): void
    {
        $this->photoElement->updateSector(
            $event->getItem()->getId(),
            $event->getCoordinates()
        );
    }

    public function updateWorldObjectAttribute(PhotoElementTouchEvent $event): void
    {
        if ($event->getItem()->getWorldObject()) {
            $this->worldObjectAttribute->update(
                $event->getItem()->getWorldObject()->getId()
            );
        }
    }
}
