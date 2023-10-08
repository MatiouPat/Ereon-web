<?php

namespace App\Repository;

use App\Entity\LightingWall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LightingWall>
 *
 * @method LightingWall|null find($id, $lockMode = null, $lockVersion = null)
 * @method LightingWall|null findOneBy(array $criteria, array $orderBy = null)
 * @method LightingWall[]    findAll()
 * @method LightingWall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LightingWallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LightingWall::class);
    }

//    /**
//     * @return LightingWall[] Returns an array of LightingWall objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LightingWall
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
