<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException; // Ajoutez cette ligne
use App\Entity\Exercise;
use Doctrine\ORM\EntityManagerInterface;

class MyExercicesController extends AbstractController
{
    #[Route('/mesexercices', name: 'app_my_exercices')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur a le rôle requis
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_TEACHER')) {
            throw new AccessDeniedException("Vous n'avez pas accès à cette page.");
        }

        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère l'EntityManager
        $repository = $entityManager->getRepository(Exercise::class);

        // Récupère les exercices créés par l'utilisateur connecté
        $exercices = $repository->findBy(['createdBy' => $user]);

        return $this->render('my_exercices/index.html.twig', [
            'exercices' => $exercices,
        ]);
    }
}
