<?php

namespace App\Entity;

use App\Entity\ConfigurationList\Condition;
use App\Entity\ConfigurationList\Element;
use App\Entity\Traits\TraceTrait;
use App\Entity\Traits\UUIDableTrait;
use App\Entity\WorldObject\WorldObject;
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
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfigurationList\Element")
     */
    private $element;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfigurationList\Condition")
     */
    private $condition;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type"="POLYGON"})
     */
    private $sector;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Photo", inversedBy="elements")
     * @ORM\JoinColumn(referencedColumnName="id", name="photo_id", nullable=false)
     */
    private ?Photo $photo = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WorldObject\WorldObject")
     * @ORM\JoinColumn(name="world_object_id", referencedColumnName="id", nullable=false)
     */
    private ?WorldObject $worldObject = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSector()
    {
        return $this->sector;
    }

    public function setSector($sector): void
    {
        $this->sector = $sector;
    }


    public function getPhoto()
    {
        return $this->photo;
    }


    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }


    public function getWorldObject(): ?WorldObject
    {
        return $this->worldObject;
    }


    public function setWorldObject(?WorldObject $worldObject): void
    {
        $this->worldObject = $worldObject;
    }
}
