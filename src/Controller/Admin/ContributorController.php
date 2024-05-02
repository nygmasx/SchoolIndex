<?php

// src/Controller/AdminController.php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContributorController extends AbstractController
{

    public function __construct(
    private readonly PaginatorInterface $paginator,
    private readonly UserRepository $userRepository,
    )
    {

    }

    #[Route('/contributeur', name: 'app_contributor')]
    public function index(Request $request): Response
    {
        // Capture le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search', '');

        // Utilisez la méthode modifiée pour obtenir un QueryBuilder basé sur le terme de recherche
        $queryBuilder = $this->userRepository->getSearchQueryBuilder($searchTerm);

        // Paginez le résultat du QueryBuilder
        $pagination = $this->paginator->paginate(
            $queryBuilder, // QueryBuilder
            $request->query->getInt('page', 1), // Numéro de la page
            5 // Limite par page
        );

        // Renvoyez le résultat à votre template, avec la pagination et le terme de recherche
        return $this->render('admin/contributor/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
        ]);
    }
}
