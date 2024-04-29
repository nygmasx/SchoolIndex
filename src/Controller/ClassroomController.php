<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'avez pas accès à cette page.");
        }

        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $classrooms = $entityManager->getRepository(Classroom::class)->findBySearchTerm($searchTerm);
        } else {
            $classrooms = $entityManager->getRepository(Classroom::class)->findAll();
        }

        // Fetch the count of related exercises for each classroom
        $classroomData = [];
        foreach ($classrooms as $classroom) {
            $classroomData[] = [
                'classroom' => $classroom,
                'exerciseCount' => $classroom->getNumberOfExercises(),
            ];
        }

        return $this->render('classroom/index.html.twig', [
            'classrooms' => $classroomData,
        ]);
    }
}
