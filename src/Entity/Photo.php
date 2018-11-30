<?php

namespace App\Entity;

use App\Entity\Traits\TraceTrait;
use App\Entity\Traits\UUIDableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
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
}
