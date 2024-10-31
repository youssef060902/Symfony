<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findSortedEmails(): array
    {
        
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT a.email 
            FROM App\Entity\Author a
            ORDER BY a.email ASC'
        );

        
        return $query->getResult();
    }

    public function searchByAuthorName(string $name): array
    {
        
        return $this->createQueryBuilder('a')
            ->where('a.username LIKE :name')
            ->setParameter('name', '%' . $name . '%')  
            ->orderBy('a.username', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
