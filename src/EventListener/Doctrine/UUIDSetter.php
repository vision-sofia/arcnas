<?php

namespace App\EventListener\Doctrine;

use App\Entity\UuidInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Ramsey\Uuid\Uuid;

class UUIDSetter
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof UuidInterface && null === $entity->getUuid()) {
            $entity->setUuid(Uuid::uuid4());

            return;
        }
    }
}
