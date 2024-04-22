<?php

namespace App\Controller;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MathematiquesController extends AbstractController
{
    #[Route('/mathematiques', name: 'app_mathematiques')]
    public function index(Request $request): Response
    {
        return $this->render('mathematiques/index.html.twig', [
        ]);
    }
}
