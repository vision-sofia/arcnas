<?php

namespace App\Entity\Traits;

trait ShortUUIDTrait
{
    /**
     * @ORM\Column(type="string", unique=true, length=32)
     */
    private $shortUuid;

    public function setShortUuid(string $uuid): void
    {
        $this->shortUuid = $uuid;
    }

    public function getShortUuid(): ?string
    {
        return $this->shortUuid;
    }
}
