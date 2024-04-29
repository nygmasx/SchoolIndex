<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Origin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrigineController extends AbstractController
{
    #[Route('/origine', name: 'app_origine')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $searchTerm = $request->query->get('search');

        $originRepository = $entityManager->getRepository(Origin::class);

        if ($searchTerm) {
            $origines = $originRepository->findBySearchTerm($searchTerm);
        } else {
            $origines = $originRepository->findAll();
        }

        return $this->render('origine/index.html.twig', [
            'origines' => $origines, // Utilisez la même variable ici
        ]);
        
    }
}
