<?php

namespace App\Entity\Traits;

trait SluggableTrait
{
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
