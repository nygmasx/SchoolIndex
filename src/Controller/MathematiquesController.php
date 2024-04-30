<?php

namespace App\Controller;

use App\Entity\Exercise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ExerciseRepository;

class MathematiquesController extends AbstractController
{
    #[Route('/mathematiques', name: 'app_mathematiques')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, ExerciseRepository $exerciseRepository): Response
    {
        // Récupérer tous les exercices liés au cours de mathématiques
        $mathExercisesQuery = $exerciseRepository->findMathExercises();
        
        // Paginer les résultats
        $pagination = $paginator->paginate(
            $mathExercisesQuery,
            $request->query->getInt('page', 1), 
            5
        );
        
        // Récupérer les trois derniers exercices liés aux mathématiques
        $latestMathExercises = $exerciseRepository->findLatestMathExercises(3);
        
        // Limite d'exercices dans le deuxième tableau
        $secondTableLimit = 5;
        
        // Récupérer les exercices pour le deuxième tableau
        $secondTableExercises = $entityManager->getRepository(Exercise::class)->findBy(['course' => 'Mathématiques'], ['createdAt' => 'DESC'], $secondTableLimit);

        return $this->render('mathematiques/index.html.twig', [
            'pagination' => $pagination,
            'latestExercises' => $latestMathExercises,
            'secondTableExercises' => $secondTableExercises,
        ]);
    }
}
