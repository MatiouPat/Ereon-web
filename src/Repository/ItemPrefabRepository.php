<?php

namespace App\Repository;

use App\Entity\ItemPrefab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemPrefab>
 *
 * @method ItemPrefab|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemPrefab|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemPrefab[]    findAll()
 * @method ItemPrefab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemPrefabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemPrefab::class);
    }

//    /**
//     * @return ItemPrefab[] Returns an array of ItemPrefab objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ItemPrefab
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
