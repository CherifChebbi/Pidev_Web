<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function searchByName(string $searchTerm): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
   



    public function advancedSearch(string $searchTerm, string $location, ?int $minPrice, ?int $maxPrice): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.plats', 'p') // Assuming 'plats' is the property representing the association with Plat entity
            ->where('r.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
    
        if ($location) {
            $queryBuilder->andWhere('r.localisation LIKE :location')
                ->setParameter('location', '%' . $location . '%');
        }
    
        if ($minPrice !== null && $maxPrice !== null) {
            // Apply filtering for the price range based on the associated Plats
            $queryBuilder->andWhere('p.prix >= :minPrice')
                ->andWhere('p.prix <= :maxPrice')
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        }
    
        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Restaurant[] Returns an array of Restaurant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Restaurant
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
