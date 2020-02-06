<?php

namespace App\Repository;

use App\Entity\Lexique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lexique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lexique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lexique[]    findAll()
 * @method Lexique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LexiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lexique::class);
    }

    // /**
    //  * @return Lexique[] Returns an array of Lexique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lexique
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
