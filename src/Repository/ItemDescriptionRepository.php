<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018–2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Repository;

use App\Entity\ItemDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|ItemDescription find($id, $lockMode = null, $lockVersion = null)
 * @method null|ItemDescription findOneBy(array $criteria, array $orderBy = null)
 * @method ItemDescription[]    findAll()
 * @method ItemDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemDescription::class);
    }

//    /**
//     * @return ItemDescription[] Returns an array of ItemDescription objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemDescription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
