<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Thematic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ResearchController extends AbstractController
{
    #[Route('/research', name: 'app_research')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $classrooms = $entityManager->getRepository(Classroom::class)->findAll();
        $thematics = $entityManager->getRepository(Thematic::class)->findAll();
        return $this->render('research/index.html.twig', [
            'classrooms' => $classrooms,
            'thematics' => $thematics
        ]);
    }
}
