<?php

namespace App\Entity\WorldObject;

use App\Entity\Traits\UUIDableTrait;
use App\Entity\UuidInterface;
use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="world_object", schema="arc_world_object")
 */
class WorldObject implements UuidInterface
{
    use UUIDableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name = '';

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    private $attributes;

    /**
     * @ORM\Column(name="coordinates", type="point_geog", nullable=true)
     */
    private $coordinates;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getAttributes():?array
    {
        return $this->attributes;
    }


    public function setAttributes($attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getCoordinates():?Point
    {
        return $this->coordinates;
    }

    public function setCoordinates(Point $coordinates): void
    {
        $this->coordinates = $coordinates;
    }



}
