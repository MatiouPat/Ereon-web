<?php

namespace App\Repository;

use App\Entity\AlterationChange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AlterationChange>
 *
 * @method AlterationChange|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlterationChange|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlterationChange[]    findAll()
 * @method AlterationChange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlterationChangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlterationChange::class);
    }

//    /**
//     * @return AlterationChange[] Returns an array of AlterationChange objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AlterationChange
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
