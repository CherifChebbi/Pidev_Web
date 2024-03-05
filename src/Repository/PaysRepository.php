<?php

namespace App\Repository;
use App\Entity\Ville;
use App\Entity\Pays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Google\Service\Docs\Request;
use Google\Service\Docs\Response;

/**
 * @extends ServiceEntityRepository<Pays>
 *
 * @method Pays|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pays|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pays[]    findAll()
 * @method Pays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pays::class);
    }

//    /**
//     * @return Pays[] Returns an array of Pays objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pays
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findVillesByPaysId(int $paysId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT v
            FROM App\Entity\Ville v
            WHERE v.pays = :paysId'
        )->setParameter('paysId', $paysId);

        return $query->getResult();
    }
    //SEARCH
    public function search($searchTerm)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom_pays LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }
    //count villes_pays
    public function countVillesByPaysId(int $paysId): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(v)')
            ->join('p.villes', 'v')
            ->where('p.id_pays = :paysId')
            ->setParameter('paysId', $paysId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    //tri
    public function findAllOrderedBy($sortBy)
    {
        $qb = $this->createQueryBuilder('p');
        if ($sortBy === 'nb_villes') {
            $qb->orderBy('p.nb_villes', 'DESC');
        } else {
            $qb->orderBy('p.nom_pays', 'ASC');
        }
        return $qb->getQuery()->getResult();
    }
    //STAT
    public function findPaysAvecPlusDeVilles()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nb_villes', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    //STATISTIQUE
    // Récupérer le nombre de pays par nombre de villes
    public function getNombreVillesParPays()
    {
        return $this->createQueryBuilder('p')
            ->select('p.nom_pays as nomPays', 'p.nb_villes as nombreVilles')
            ->getQuery()
            ->getResult();
    }

    // Récupérer le nombre de pays par continent
    public function getPaysParContinent()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p) as nombrePays', 'p.continent as continent')
            ->groupBy('p.continent')
            ->getQuery()
            ->getResult();
    }

}