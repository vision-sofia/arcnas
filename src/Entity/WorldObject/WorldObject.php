<?php

namespace App\Entity\WorldObject;

use App\Entity\Traits\UUIDableTrait;
use App\Entity\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use Jsor\Doctrine\PostGIS\Types\GeometryType;

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
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private string $name = '';

    /**
     * @ORM\Column(type="json", options={"jsonb": true}, nullable=true)
     */
    private ?array $attributes = null;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type"="POINT"}, nullable=true)
     */
    private ?GeometryType $coordinates = null;

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

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }


    public function setAttributes($attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function setCoordinates($coordinates): void
    {
        $this->coordinates = $coordinates;
    }
}
