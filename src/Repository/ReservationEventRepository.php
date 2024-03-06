<?php

namespace App\Repository;

use App\Entity\ReservationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationEvent>
 *
 * @method ReservationEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationEvent[]    findAll()
 * @method ReservationEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationEvent::class);
    }

    public function countReservationsByEvent()
{
    $qb = $this->createQueryBuilder('r')
        ->select('e.titre AS eventName, COUNT(r.id) AS reservationsCount')
        ->leftJoin('r.id_event', 'e')
        ->groupBy('eventName')
        ->orderBy('eventName', 'ASC');

    return $qb->getQuery()->getResult();
}


//    /**
//     * @return ReservationEvent[] Returns an array of ReservationEvent objects
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

//    public function findOneBySomeField($value): ?ReservationEvent
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
