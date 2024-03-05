<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ville>
 *
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

//    /**
//     * @return Ville[] Returns an array of Ville objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ville
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findMonumentsByVilleId(int $villeId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT m
            FROM App\Entity\Monument m
            WHERE m.villes = :villeId'
        )->setParameter('villeId', $villeId);

        return $query->getResult();
    }
    //SEARCH
    public function search($searchTerm)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom_ville LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }
    //count mon_ville
    public function countMonByVille(int $villeId): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(v)')
            ->join('p.monuments', 'v')
            ->where('p.id_ville = :villeId')
            ->setParameter('villeId', $villeId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    //STAT
    public function findVilleAvecPlusDeMonuments()
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.nb_monuments', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
