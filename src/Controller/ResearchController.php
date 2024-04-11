<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends AbstractController
{
    #[Route(path: '/research', name: 'app_research')]
        public function index(Request $request):Response{
            return $this->render('research/index.html.twig', [
            ]);
        }
}