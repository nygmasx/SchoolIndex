<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'avez pas accès à cette page.");
        }

        $classrooms = $entityManager->getRepository(Classroom::class)->findAll();

        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
            'classrooms' => $classrooms,
        ]);
    }
}
