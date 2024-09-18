<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * findByQuery
     * Méthode pour la recherch de note dans l'appli Codexpress
     * @param string $query
     * @return array Returns an array of Note objects
     */
    public function findByQuery($query): array
    {
        return $this->createQueryBuilder('n') //nomer premiere lettre n
            ->where('n.is_public = true') // 
            ->andWhere('n.title LIKE :q OR n.content LIKE :q') //
            ->setParameter('q', '%'. $query . '%')
            ->orderBy('n.created_at', 'DESC')
            ->getQuery() // form associative array
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Note
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
