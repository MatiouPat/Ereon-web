<?php

namespace App\Repository;

use App\Entity\DamageOrResistance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DamageOrResistance>
 *
 * @method DamageOrResistance|null find($id, $lockMode = null, $lockVersion = null)
 * @method DamageOrResistance|null findOneBy(array $criteria, array $orderBy = null)
 * @method DamageOrResistance[]    findAll()
 * @method DamageOrResistance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DamageOrResistanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DamageOrResistance::class);
    }

//    /**
//     * @return DamageOrResistance[] Returns an array of DamageOrResistance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DamageOrResistance
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
