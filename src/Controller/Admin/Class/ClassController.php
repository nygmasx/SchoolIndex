<?php

namespace App\Controller\Admin\Class;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClassController extends AbstractController
{
    #[Route('/class', name: 'app_class')]
    public function index(): Response
    {
        return $this->render('admin/class/index.html.twig', [
            'controller_name' => 'ClassController',
        ]);
    }
}
