<?php

namespace App\Repository;

use App\Entity\Story;
use App\Entity\UserProfile;
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
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $qb->orderBy('s.' . $orderBy, $orderDir);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Story[] Returns an array of Story objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Story
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
