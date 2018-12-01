<?php

namespace App\Event;

use App\Entity\PhotoElement;
use Symfony\Component\EventDispatcher\Event;

class PhotoElementTouchEvent extends Event
{
    public const NAME = 'photo.element.touch';

    protected $item;
    protected $coordinates;

    public function __construct(PhotoElement $item, string $coordinates)
    {
        $this->item = $item;
        $this->coordinates = $coordinates;
    }

    public function getItem(): PhotoElement
    {
        return $this->item;
    }

    public function setItem(PhotoElement $item): void
    {
        $this->item = $item;
    }

    public function getCoordinates():?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(float $coordinates): void
    {
        $this->coordinates = $coordinates;
    }
}
