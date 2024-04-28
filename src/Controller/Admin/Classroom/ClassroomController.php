<?php

namespace App\Controller\Admin\Classroom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Knp\Component\Pager\PaginatorInterface;


class ClassroomController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly PaginatorInterface $paginator, 
        private readonly ClassroomRepository $classroomRepository, 
    ) {}

    #[Route('/classroom/new', name: 'new_classroom')]
    public function addClassroom(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    

            $this->entityManager->persist($classroom);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_classroom');
        }

        return $this->render('admin/classroom/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/classroom/delete/{id}', name: 'classroom_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, classroom $classroom, AuthorizationCheckerInterface $authChecker, EntityManagerInterface $entityManager): Response
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


    #[Route('/classroom/edit/{id}', name: 'edit_classroom')]
    public function editClassroom(Classroom $classroom, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }
        
        $form = $this->createForm(ClassroomType::class, $classroom, [
            'require_password' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

            return $this->redirectToRoute('app_classroom');
        }

        return $this->render('admin/classroom/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/classroom', name: 'app_classroom')]
    public function index(Request $request): Response
    {
        // Capture le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search', '');

        // Utilisez la méthode modifiée pour obtenir un QueryBuilder basé sur le terme de recherche
        $queryBuilder = $this->classroomRepository->getSearchQueryBuilder($searchTerm);

        // Paginez le résultat du QueryBuilder
        $pagination = $this->paginator->paginate(
            $queryBuilder, // QueryBuilder
            $request->query->getInt('page', 1), // Numéro de la page
            4 // Limite par page
        );

        // Renvoyez le résultat à votre template, avec la pagination et le terme de recherche
        return $this->render('admin/classroom/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
        ]);
    }


}