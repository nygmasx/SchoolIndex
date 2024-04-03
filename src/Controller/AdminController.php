<?php 

// src/Controller/AdminController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\UserRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
public function index(Request $request, PaginatorInterface $paginator, UserRepository $userRepository): Response
{
    // Capture le terme de recherche depuis la requête
    $searchTerm = $request->query->get('search', '');

    // Utilisez la méthode modifiée pour obtenir un QueryBuilder basé sur le terme de recherche
    $queryBuilder = $userRepository->getSearchQueryBuilder($searchTerm);

    // Paginez le résultat du QueryBuilder
    $pagination = $paginator->paginate(
        $queryBuilder, // QueryBuilder
        $request->query->getInt('page', 1), // Numéro de la page
        4 // Limite par page
    );

    // Renvoyez le résultat à votre template, avec la pagination et le terme de recherche
    return $this->render('admin/index.html.twig', [
        'pagination' => $pagination,
        'searchTerm' => $searchTerm,
    ]);
}

    
}
