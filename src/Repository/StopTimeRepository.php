<?php

namespace App\Repository;

use App\Entity\StopTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StopTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method StopTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method StopTime[]    findAll()
 * @method StopTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StopTimeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StopTime::class);
    }

    // /**
    //  * @return StopTime[] Returns an array of StopTime objects
    //  */

    public function findByStopId($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.stop_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

     // /**
    //  * @return StopTime[] Returns an array of StopTime objects
    //  */
    public function findByTripId($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.trip_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
       
    }

    /*
    public function findOneBySomeField($value): ?StopTime
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
