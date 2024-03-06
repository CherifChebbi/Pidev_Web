<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    public function findByCategory($idCategory)
    {
        return $this->createQueryBuilder('e')
            ->join('e.idCategory', 'c')
            ->andWhere('c.id = :idCategory')
            ->setParameter('idCategory', $idCategory)
            ->getQuery()
            ->getResult();
    }



   public function findAllLieux()
{
    $lieux = $this->createQueryBuilder('e')
        ->select('e.lieu')
        ->distinct(true)
        ->getQuery()
        ->getResult();

    // Transformez le tableau associatif en tableau de chaînes de caractères
    $lieuxArray = [];
    foreach ($lieux as $lieu) {
        $lieuxArray[] = $lieu['lieu'];
    }

    return $lieuxArray;
}

    public function findEventsByClosestDate()
    {
        return $this->createQueryBuilder('e')
            ->where('e.date_debut >= :currentDate') // Récupérer les événements dont la date est supérieure ou égale à la date actuelle
            ->setParameter('currentDate', new \DateTime()) // Date actuelle
            ->orderBy('e.date_debut', 'ASC') // Trier par date croissante (la plus proche en premier)
            ->getQuery()
            ->getResult();
    }

    public function findByPriceAscending()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByPriceDescending()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByTitleAscending()
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.titre', 'ASC') // Tri par titre A-Z
        ->getQuery()
        ->getResult();
}

public function findByTitleDescending()
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.titre', 'DESC') // Tri par titre Z-A
        ->getQuery()
        ->getResult();

}

}

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
