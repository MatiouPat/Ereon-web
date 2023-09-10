<?php

namespace App\Repository;

use App\Entity\DamageOrResistanceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DamageOrResistanceType>
 *
 * @method DamageOrResistanceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DamageOrResistanceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DamageOrResistanceType[]    findAll()
 * @method DamageOrResistanceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DamageOrResistanceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DamageOrResistanceType::class);
    }

//    /**
//     * @return DamageOrResistanceType[] Returns an array of DamageOrResistanceType objects
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

//    public function findOneBySomeField($value): ?DamageOrResistanceType
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
