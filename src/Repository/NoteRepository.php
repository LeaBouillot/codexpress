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
     * finByQuery
     * Méthode pour la recherche de note dans l'application CodeXpress
     * @param string $query
     * @return array
     */
    public function findByQuery($query): array //Search
    {
        return $this->createQueryBuilder('n') //method (nomer premiere lettre n)
            ->where('n.is_public = true') // 
            ->andWhere('n.title LIKE :q OR n.content LIKE :q') //
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('n.created_at', 'DESC')
            ->getQuery() // form associative array
            ->getResult()
        ;
    }

    public function findByCreator($id): array //modofier à NoteController 45/
    {
        return $this->createQueryBuilder('n')
            ->where('n.is_public = true')
            ->andWhere('n.creator = :id') //dans le bdd champ name
            ->setParameter('id', $id)
            ->orderBy('n.created_at', 'DESC')
            ->setMaxResults(3) //limitation à 3 resultat
            ->getQuery()
            ->getResult()
        ;
    }
}
