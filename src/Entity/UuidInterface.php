<?php

namespace App\Entity;

interface UuidInterface
{
    public function getUuid(): ?string;

    public function setUuid(string $uuid): void;
}
