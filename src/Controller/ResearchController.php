<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends AbstractController
{
    #[Route('/research', name: 'app_research')]
    public function index(): Response
    {
        return $this->render('research/index.html.twig', [
            'controller_name' => 'ResearchController',
        ]);
    }
}
