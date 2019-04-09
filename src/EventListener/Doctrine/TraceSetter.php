<?php

namespace App\EventListener\Doctrine;

use App\Entity\TraceableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TraceSetter
{
    protected $user;

    public function __construct(TokenStorageInterface $user)
    {
        $this->user = $user;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof TraceableInterface) {
            $entity->setAddedAt(new \DateTimeImmutable());

            if ($this->user->getToken()) {
                $entity->setAddedBy($this->user->getToken()->getUser());
            }

            return;
        }
    }
}
