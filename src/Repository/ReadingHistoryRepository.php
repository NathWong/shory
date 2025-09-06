<?php

namespace App\Repository;

use App\Entity\ReadingHistory;
use App\Entity\Story;
use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadingHistory>
 */
class ReadingHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadingHistory::class);
    }

    public function countUniqueChaptersRead(UserProfile $userProfile, Story $story): int
    {
        return (int) $this->createQueryBuilder('rh')
            ->select('COUNT(DISTINCT cl.target)')
            ->join('rh.chapterLink', 'cl')
            ->where('rh.userProfile = :userProfile')
            ->andWhere('rh.story = :story')
            ->setParameter('userProfile', $userProfile)
            ->setParameter('story', $story)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
