<?php

namespace App\Controller\Contributors;

use App\Entity\Course;
use App\Entity\Exercise;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/matiere/{name}', name: 'app_course')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, ExerciseRepository $exerciseRepository, string $name, Course $course): Response
    {
        // Récupérer le nom de la matière depuis la requête (par exemple)
        $matiere = $request->query->get('matiere');

        // Récupérer tous les exercices liés à la matière sélectionnée
        $matiereExercisesQuery = $exerciseRepository->findExercisesByCourseName($name);

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $matiereExercisesQuery,
            $request->query->getInt('page', 1),
            5
        );

        // Récupérer les trois derniers exercices liés à la matière sélectionnée
        $latestMatiereExercises = $exerciseRepository->findLatestExercises($course, 3);

        // Limite d'exercices dans le deuxième tableau
        $secondTableLimit = 5;

        // Récupérer les exercices pour le deuxième tableau
        $secondTableExercises = $entityManager->getRepository(Exercise::class)->findBy(['course' => $matiere], ['createdAt' => 'DESC'], $secondTableLimit);

        return $this->render('contributors/course/index.html.twig', [
            'course' => $course,
            'matiere' => $matiere,
            'pagination' => $pagination,
            'latestExercises' => $latestMatiereExercises,
            'secondTableExercises' => $secondTableExercises,
        ]);
    }
}
