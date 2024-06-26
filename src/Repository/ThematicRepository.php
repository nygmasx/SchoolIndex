<?php

namespace App\Repository;

use App\Entity\Thematic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Thematic>
 *
 * @method Thematic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematic[]    findAll()
 * @method Thematic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematic::class);
    }

    public function findBySearchTerm(string $searchTerm): array
        {
        
            return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :searchTerm OR u.surname LIKE :searchTerm OR u.email LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
        }

    public function getSearchQueryBuilder(string $searchTerm = ''): QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        if ($searchTerm) {
            $qb->andWhere('u.name LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        return $qb; // Ajoutez cette ligne pour corriger l'erreur
    }

//    /**
//     * @return Thematic[] Returns an array of Thematic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Thematic
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}