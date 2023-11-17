<?php

namespace App\Repository;

use App\Entity\ArmorPrefab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArmorPrefab>
 *
 * @method ArmorPrefab|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArmorPrefab|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArmorPrefab[]    findAll()
 * @method ArmorPrefab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArmorPrefabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArmorPrefab::class);
    }

//    /**
//     * @return ArmorPrefab[] Returns an array of ArmorPrefab objects
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

//    public function findOneBySomeField($value): ?ArmorPrefab
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
