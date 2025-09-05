<?php

namespace App\Repository;

use App\Entity\ModerationMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModerationMessage>
 *
 * @method ModerationMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModerationMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModerationMessage[]    findAll()
 * @method ModerationMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModerationMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModerationMessage::class);
    }

//    /**
//     * @return ModerationMessage[] Returns an array of ModerationMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModerationMessage
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
