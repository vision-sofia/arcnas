<?php

namespace App\Entity\ConfigurationList;

use App\Entity\Traits\UUIDableTrait;
use App\Entity\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="element", schema="configuration_list")
 */
class Element implements UuidInterface
{
    use UUIDableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $primaryColor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor($primaryColor): void
    {
        $this->primaryColor = $primaryColor;
    }
}
