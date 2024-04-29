<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Origin;
use App\Form\OriginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrigineController extends AbstractController
{
    #[Route('/origine', name: 'app_origin')]
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

        return $this->render('origine/add.html.twig', [
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
    
        return $this->render('origine/edit.html.twig', [
            'form' => $form->createView(),
            'origin' => $origin, // Passer l'origine au template au cas où vous avez besoin de l'afficher
        ]);
    }
    
}
