<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Ajout de cette ligne
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Classroom;
use App\Repository\ResearchRepository;
use App\Repository\ComponentsRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResearchController extends AbstractController
{
    #[Route(path: '/research', name: 'app_research')]
        public function index(Request $request):Response{
            return $this->render('research/index.html.twig', [
            ]);
        }
}

