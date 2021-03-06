<?php

namespace App\Entity\Traits;

use Symfony\Component\Security\Core\User\UserInterface;

trait TraceTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false, name="added_by")
     */
    private $addedBy;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $addedAt;

    public function getAddedBy(): UserInterface
    {
        return $this->addedBy;
    }

    public function setAddedBy(UserInterface $addedBy): void
    {
        $this->addedBy = $addedBy;
    }

    public function setAddedAt(\DateTimeImmutable $dateTimeImmutable): void
    {
        $this->addedAt = $dateTimeImmutable;
    }

    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->addedAt;
    }
}
