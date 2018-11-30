<?php

namespace App\Entity\User;

use App\Entity\UuidInterface;

interface UserInterface extends UuidInterface
{
    public function getId(): int;

    public function getUsername(): ?string;

    public function setUsername(string $username);
}
