<?php

namespace App\Repository;

use App\Entity\StoryGroup;
use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StoryGroup>
 */
class StoryGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryGroup::class);
    }

    public function findByOwner(UserProfile $owner, ?string $q, string $sort): array
    {
        $qb = $this->createQueryBuilder('sg');
        $qb->andWhere('sg.owner = :owner')
            ->setParameter('owner', $owner->getId());
        if ($q) {
            $qb->andWhere('sg.title LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $q . '%');
        }
        $qb->orderBy('sg.' . $sort, 'DESC');

        return $qb->getQuery()->getResult();
    }
}
