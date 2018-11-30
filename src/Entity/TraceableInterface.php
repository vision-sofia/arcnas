<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface TraceableInterface
{
    public function setAddedBy(UserInterface $user): void;

    public function getAddedBy(): UserInterface;

    public function setAddedAt(\DateTimeImmutable $dateTime): void;

    public function getAddedAt(): \DateTimeImmutable;
}
