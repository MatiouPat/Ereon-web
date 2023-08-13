<?php

namespace App\Repository;

use App\Entity\UserWorldParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserWorldParameter>
 *
 * @method UserWorldParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWorldParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWorldParameter[]    findAll()
 * @method UserWorldParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWorldParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWorldParameter::class);
    }

    public function save(UserWorldParameter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserWorldParameter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserWorldParameter[] Returns an array of UserWorldParameter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserWorldParameter
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
