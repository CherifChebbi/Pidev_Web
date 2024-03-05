<?php

namespace App\Repository;

use App\Entity\Monument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monument>
 *
 * @method Monument|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monument|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monument[]    findAll()
 * @method Monument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monument::class);
    }

//    /**
//     * @return Monument[] Returns an array of Monument objects
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

//    public function findOneBySomeField($value): ?Monument
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
//SEARCH
public function search($searchTerm)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.nom_monument LIKE :searchTerm')
        ->setParameter('searchTerm', '%'.$searchTerm.'%')
        ->getQuery()
        ->getResult();
}
}
