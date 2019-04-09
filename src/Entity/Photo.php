<?php

namespace App\Entity;

use App\Entity\Traits\TraceTrait;
use App\Entity\Traits\UUIDableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(name="photo", schema="arc_photo")
 */
class Photo implements UuidInterface, TraceableInterface, UploadableFileInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     */
    private $metadata;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhotoElement", mappedBy="photo")
     */
    private $elements;

    /**
     * Photo constructor.
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param UploadedFile|string $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile|string
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    /** @return PhotoElement[]|Collection */
    public function getElements():?Collection
    {
        return $this->elements;
    }


    public function setElements($elements): void
    {
        $this->elements = $elements;
    }
}
