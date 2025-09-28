<?php

namespace App\Repository;

use App\Entity\Story;
use App\Entity\UserProfile;
use App\Enum\StoryStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Story>
 */
class StoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Story::class);
    }

    public function findByFilters(
        UserProfile $userProfile,
        ?string $searchTerm,
        string $orderBy = 'updatedAt',
        string $orderDir = 'DESC',
    ): array {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.owner = :owner')
            ->setParameter('owner', $userProfile->getId());

        if ($searchTerm) {
            $qb->andWhere('s.title LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $qb->orderBy('s.'.$orderBy, $orderDir);

        return $qb->getQuery()->getResult();
    }

    public function findByStoryStatus(StoryStatus $WAITING_VALIDATION, ?string $q, string $sort): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.storyStatus = :status')
            ->setParameter('status', $WAITING_VALIDATION->value);
        if ($q) {
            $qb->andWhere('s.title LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$q.'%');
        }
        $qb->orderBy('s.'.$sort, 'DESC');

        return $qb->getQuery()->getResult();
    }
}
