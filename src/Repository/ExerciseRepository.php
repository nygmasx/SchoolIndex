<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 *
 * @method Exercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercise[]    findAll()
 * @method Exercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    public function findMathExercises()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.course', 'c')
            ->andWhere('c.name = :courseName')
            ->setParameter('courseName', 'Mathématiques')
            ->getQuery();
    }

    public function findLatestMathExercises($limit)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.course', 'c')
            ->andWhere('c.name = :courseName')
            ->setParameter('courseName', 'Mathématiques')
            ->orderBy('e.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByCourseName($courseName)
    {
        return $this->createQueryBuilder('e')
            ->join('e.course', 'c')
            ->andWhere('c.name = :courseName')
            ->setParameter('courseName', $courseName)
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher les exercices par nom de classe
    public function findByClassName($className)
    {
        return $this->createQueryBuilder('e')
            ->join('e.classroom', 'cl')
            ->andWhere('cl.name = :className')
            ->setParameter('className', $className)
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher les exercices par nom de thématique
    public function findByThematicName($thematicName)
    {
        return $this->createQueryBuilder('e')
            ->join('e.thematic', 't')
            ->andWhere('t.name = :thematicName')
            ->setParameter('thematicName', $thematicName)
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher les exercices par mots-clés
    public function findByKeywords($keywords)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.keywords LIKE :keywords')
            ->setParameter('keywords', '%' . $keywords . '%')
            ->getQuery()
            ->getResult();
    }
    
    
    


//    /**
//     * @return Exercise[] Returns an array of Exercise objects
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

//    public function findOneBySomeField($value): ?Exercise
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function save(Exercise $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }
}
