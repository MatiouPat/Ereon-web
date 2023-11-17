<?php

namespace App\Repository;

use App\Entity\WeaponPrefab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeaponPrefab>
 *
 * @method WeaponPrefab|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeaponPrefab|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeaponPrefab[]    findAll()
 * @method WeaponPrefab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeaponPrefabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeaponPrefab::class);
    }

//    /**
//     * @return WeaponPrefab[] Returns an array of WeaponPrefab objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WeaponPrefab
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
