<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Thematic;
use App\Entity\Course;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends AbstractController
{
    #[Route('/research', name: 'app_research')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, ExerciseRepository $exerciseRepository): Response
    {
        // Récupérer les paramètres de recherche depuis la requête
        $courseName = $request->query->get('course');
        $className = $request->query->get('class');
        $thematicName = $request->query->get('thematic');
        $keywords = $request->query->get('keywords');

        // Récupérer tous les exercices
        $exercisesQuery = $exerciseRepository->findAll();

        // Filtrer les exercices en fonction des paramètres de recherche
        if ($courseName) {
            $exercisesQuery = $exerciseRepository->findByCourseName($courseName);
        }

        if ($className) {
            $exercisesQuery = $exerciseRepository->findByClassName($className);
        }

        if ($thematicName) {
            $exercisesQuery = $exerciseRepository->findByThematicName($thematicName);
        }

        if ($keywords) {
            $exercisesQuery = $exerciseRepository->findByKeywords($keywords);
        }

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $exercisesQuery,
            $request->query->getInt('page', 1),
            5 // Nombre d'exercices par page
        );

        // Récupérer toutes les classes, thématiques et matières
        $classrooms = $entityManager->getRepository(Classroom::class)->findAll();
        $thematics = $entityManager->getRepository(Thematic::class)->findAll();
        $courses = $entityManager->getRepository(Course::class)->findAll();

        return $this->render('research/index.html.twig', [
            'pagination' => $pagination,
            'classrooms' => $classrooms,
            'thematics' => $thematics,
            'courses' => $courses,
        ]);
    }
}
