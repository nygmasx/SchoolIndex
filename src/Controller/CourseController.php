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

class CourseController extends AbstractController
{
    #[Route('/matieres', name: 'app_matieres')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, ExerciseRepository $exerciseRepository): Response
    {
        // Récupérer le nom de la matière depuis la requête (par exemple)
        $matiere = $request->query->get('matiere');

        // Récupérer tous les exercices liés à la matière sélectionnée
        $matiereExercisesQuery = $exerciseRepository->findMatiereExercises($matiere);
        
        // Paginer les résultats
        $pagination = $paginator->paginate(
            $matiereExercisesQuery,
            $request->query->getInt('page', 1), 
            5
        );

        // Récupérer les trois derniers exercices liés à la matière sélectionnée
        $latestMatiereExercises = $exerciseRepository->findLatestMatiereExercises($matiere, 3);
        
        // Limite d'exercices dans le deuxième tableau
        $secondTableLimit = 5;
        
        // Récupérer les exercices pour le deuxième tableau
        $secondTableExercises = $entityManager->getRepository(Exercise::class)->findBy(['course' => $matiere], ['createdAt' => 'DESC'], $secondTableLimit);

        return $this->render('matieres/index.html.twig', [
            'matiere' => $matiere,
            'pagination' => $pagination,
            'latestExercises' => $latestMatiereExercises,
            'secondTableExercises' => $secondTableExercises,
        ]);
    }
}
