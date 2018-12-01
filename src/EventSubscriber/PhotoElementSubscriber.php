<?php

namespace App\EventSubscriber;


use App\Event\Events;
use App\Event\PhotoElementTouchEvent;
use App\Services\Database\PhotoElement;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PhotoElementSubscriber implements EventSubscriberInterface
{
    protected $photoElement;

    public function __construct(PhotoElement $photoElement)
    {
        $this->photoElement = $photoElement;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::PHOTO_ELEMENT_INSERT => [
                ['updateSector', 70],
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
}
