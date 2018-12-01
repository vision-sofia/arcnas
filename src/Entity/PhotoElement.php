<?php

namespace App\Entity;

use App\Entity\ConfigurationList\Condition;
use App\Entity\ConfigurationList\Element;
use App\Entity\Traits\TraceTrait;
use App\Entity\Traits\UUIDableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="element", schema="arc_photo")
 */
class PhotoElement implements UuidInterface, TraceableInterface
{
    use UUIDableTrait;
    use TraceTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfigurationList\Element")
     */
    private $element;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfigurationList\Condition")
     */
    private $condition;

    /**
     * @ORM\Column(name="sector", type="polygon_geom", nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="float", scale=2, precision=4)
     */
    private $aX;

    /**
     * @ORM\Column(type="float", scale=2, precision=4)
     */
    private $aY;

    /**
     * @ORM\Column(type="float", scale=2, precision=4)
     */
    private $bX;

    /**
     * @ORM\Column(type="float", scale=2, precision=4)
     */
    private $bY;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAX()
    {
        return $this->aX;
    }

    public function setAX(?float $aX): void
    {
        $this->aX = $aX;
    }

    public function getAY()
    {
        return $this->aY;
    }

    public function setAY(?float $aY): void
    {
        $this->aY = $aY;
    }

    public function getBX()
    {
        return $this->bX;
    }

    public function setBX(?float $bX): void
    {
        $this->bX = $bX;
    }

    public function getBY()
    {
        return $this->bY;
    }

    public function setBY(?float $bY): void
    {
        $this->bY = $bY;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): void
    {
        $this->element = $element;
    }

    public function getCondition(): ?Condition
    {
        return $this->condition;
    }

    public function setCondition(?Condition $condition): void
    {
        $this->condition = $condition;
    }
}
