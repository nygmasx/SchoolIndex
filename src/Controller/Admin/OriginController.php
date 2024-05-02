<?php

namespace App\Controller\Admin;

use App\Entity\Origin;
use App\Form\OriginType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OriginController extends AbstractController
{
    #[Route('/origine', name: 'app_origin')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search');

        $originRepository = $entityManager->getRepository(Origin::class);

        // Utilisez une méthode de recherche personnalisée si un terme de recherche est spécifié
        if ($searchTerm) {
            $originesQuery = $originRepository->findBySearchTerm($searchTerm);
        } else {
            $originesQuery = $originRepository->findAll();
        }

        // Paginer les résultats de la requête
        $origines = $paginator->paginate(
            $originesQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de la page
            5 // Nombre d'éléments par page
        );

        return $this->render('admin/origin/index.html.twig', [
            'origines' => $origines,
        ]);
    }
        
    #[Route('/origine/create', name: 'add_origin')]
    public function addOrigin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $origin = new Origin();

        $form = $this->createForm(OriginType::class, $origin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($origin);
            $entityManager->flush();

            return $this->redirectToRoute('app_origin');
        }

        return $this->render('admin/origin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/origine/edit/{id}', name: 'edit_origin')]
    public function editOrigin(Request $request, EntityManagerInterface $entityManager, Origin $origin): Response
    {
        $form = $this->createForm(OriginType::class, $origin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Pas besoin de persist(), car $origin est déjà dans l'entityManager
            $entityManager->flush();

            // Rediriger vers la liste des origines
            return $this->redirectToRoute('app_origin');
        }

        return $this->render('admin/origin/edit.html.twig', [
            'form' => $form->createView(),
            'origin' => $origin, // Passer l'origine au template au cas où vous avez besoin de l'afficher
        ]);
    }

    #[Route('/origine/delete/{id}', name: 'delete_origin', methods: ['GET', 'POST'])]
    public function delete(Request $request, Origin $origin, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire de confirmation
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($origin);
            $entityManager->flush();

            $this->addFlash('success', 'L\'origine a été supprimée avec succès.');

            return $this->redirectToRoute('app_origin');
        }

        return $this->render('admin/origin/delete.html.twig', [
            'origin' => $origin,
            'confirmationForm' => $form->createView(),
        ]);
    }


}
