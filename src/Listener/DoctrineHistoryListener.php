<?php

namespace App\Listener;

use App\Attribute\DoctrineHistory;
use App\Entity\DoctrineHistory as DoctrineHistoryEntity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preFlush, priority: 500, connection: 'default')]
class DoctrineHistoryListener
{
    public function postFlush(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $historyLength = $this->needHistory($entity);
        if (false === $historyLength) {
            return;
        }

        $uow = $args->getObjectManager()->getUnitOfWork();
        $fieldChanges = $uow->getEntityChangeSet($entity);

        $history = $this->createHistory($entity, $fieldChanges);
        $args->getObjectManager()->persist($history);
    }

    private function needHistory(object $entity): int|false
    {
        $reflection = new \ReflectionClass($entity);
        $attributes = $reflection->getAttributes(DoctrineHistory::class);
        if ($attributes) {
            return $attributes[0]->newInstance()->getHistoryLength();
        } else {
            return false;
        }
    }

    private function createHistory(object $entity, array $changeSet): DoctrineHistoryEntity
    {
        $history = new DoctrineHistoryEntity();
        $history->setObjectClass($entity::class);
        $history->setObjectId($entity->getId());
        $history->setChange($changeSet);

        return $history;
    }
}
