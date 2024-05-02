<?php

namespace App\Controller\Admin;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class ClassroomController extends AbstractController
{

    #[Route('/classe', name: 'app_classroom')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'avez pas accès à cette page.");
        }

        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $queryBuilder = $entityManager->getRepository(Classroom::class)->findBySearchTermQueryBuilder($searchTerm);
        } else {
            $queryBuilder = $entityManager->getRepository(Classroom::class)->createQueryBuilder('c');
        }

        // Paginate the query
        $pagination = $paginator->paginate(
            $queryBuilder, // Query builder
            $request->query->getInt('page', 1), // Current page number
            5 // Number of items per page
        );

        return $this->render('admin/classroom/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    #[Route('/classe/create', name: 'add_classroom')]
    public function addClassroom(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créez un nouvel objet Classroom
        $classroom = new Classroom();

        // Créez un formulaire pour traiter les données
        $form = $this->createForm(ClassroomType::class, $classroom); // Modifier ici

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        // Vérifiez si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez la nouvelle classe en base de données
            $entityManager->persist($classroom);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une page appropriée (par exemple, la liste des classes)
            return $this->redirectToRoute('app_classroom');
        }

        // Affichez le formulaire pour permettre à l'utilisateur d'ajouter une nouvelle classe
        return $this->render('admin/classroom/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/classe/edit/{id}', name: 'edit_classroom')]
    public function editClassroom(Request $request, EntityManagerInterface $entityManager, Classroom $classroom): Response
    {
        // Créez un formulaire pour traiter les données de la classe
        $form = $this->createForm(ClassroomType::class, $classroom);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        // Vérifiez si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez les modifications de la classe en base de données
            $entityManager->flush();

            // Redirigez l'utilisateur vers une page appropriée (par exemple, la liste des classes)
            return $this->redirectToRoute('app_classroom');
        }

        // Affichez le formulaire pour permettre à l'utilisateur de modifier la classe
        return $this->render('admin/classroom/edit.html.twig', [
            'form' => $form->createView(),
            'classroom' => $classroom, // Assurez-vous de transmettre la variable classroom au modèle
        ]);
    }

    #[Route('/classe/delete/{id}', name: 'classroom_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Classroom $classroom, AuthorizationCheckerInterface $authChecker, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }


        // Création du formulaire de confirmation
        $form = $this->createFormBuilder()
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($classroom);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

            return $this->redirectToRoute('app_classroom');
        }

        return $this->render('admin/classroom/delete.html.twig', [
            'classroom' => $classroom,
            'confirmationForm' => $form->createView(),
        ]);

    }


}
