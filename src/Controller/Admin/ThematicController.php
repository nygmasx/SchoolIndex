<?php

namespace App\Controller\Admin;

use App\Entity\Thematic;
use App\Form\ThematicType;
use App\Repository\ThematicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ThematicController extends AbstractController
{
        public function __construct(
            private EntityManagerInterface $entityManager,
            private readonly PaginatorInterface $paginator,
            private readonly ThematicRepository $thematicRepository,
        ) {}
<<<<<<< HEAD:src/Controller/Admin/Thematic/ThematicController.php
    
        #[Route('/thematique/create', name: 'new_thematic')]
=======

        #[Route('/thematiques/create', name: 'new_thematic')]
>>>>>>> f909c23d1bb4528a218bc41fa530dde00eb43e55:src/Controller/Admin/ThematicController.php
        public function addthematic(Request $request, AuthorizationCheckerInterface $authChecker): Response
        {

            // Vérifie si l'utilisateur a le rôle admin
            if (!$authChecker->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            }

            $thematic = new Thematic();
            $form = $this->createForm(ThematicType::class, $thematic);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                $this->entityManager->persist($thematic);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_thematic');
            }

            return $this->render('admin/thematic/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
<<<<<<< HEAD:src/Controller/Admin/Thematic/ThematicController.php
    
        #[Route('/thematique/delete/{id}', name: 'thematic_delete', methods: ['GET', 'POST'])]
=======

        #[Route('/thematiques/delete/{id}', name: 'thematic_delete', methods: ['GET', 'POST'])]
>>>>>>> f909c23d1bb4528a218bc41fa530dde00eb43e55:src/Controller/Admin/ThematicController.php
        public function delete(Request $request, thematic $thematic, AuthorizationCheckerInterface $authChecker, EntityManagerInterface $entityManager): Response
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
                $entityManager->remove($thematic);
                $entityManager->flush();

                $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

                return $this->redirectToRoute('app_thematic');
            }

            return $this->render('admin/thematic/delete.html.twig', [
                'thematic' => $thematic,
                'confirmationForm' => $form->createView(),
            ]);
        }
<<<<<<< HEAD:src/Controller/Admin/Thematic/ThematicController.php
    
    
        #[Route('/thematique/edit/{id}', name: 'edit_thematic')]
=======


        #[Route('/thematiques/edit/{id}', name: 'edit_thematic')]
>>>>>>> f909c23d1bb4528a218bc41fa530dde00eb43e55:src/Controller/Admin/ThematicController.php
        public function editthematic(thematic $thematic, Request $request, AuthorizationCheckerInterface $authChecker): Response
        {

            // Vérifie si l'utilisateur a le rôle admin
            if (!$authChecker->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            }

            $form = $this->createForm(ThematicType::class, $thematic, [
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->entityManager->flush();

                $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

                return $this->redirectToRoute('app_thematic');
            }

            return $this->render('admin/thematic/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }
<<<<<<< HEAD:src/Controller/Admin/Thematic/ThematicController.php
    
        #[Route('/thematique', name: 'app_thematic')]
=======

        #[Route('/thematiques', name: 'app_thematic')]
>>>>>>> f909c23d1bb4528a218bc41fa530dde00eb43e55:src/Controller/Admin/ThematicController.php
        public function index(Request $request): Response
        {
            // Capture le terme de recherche depuis la requête
            $searchTerm = $request->query->get('search', '');

            // Utilisez la méthode modifiée pour obtenir un QueryBuilder basé sur le terme de recherche
            $queryBuilder = $this->thematicRepository->getSearchQueryBuilder($searchTerm);

            // Paginez le résultat du QueryBuilder
            $pagination = $this->paginator->paginate(
                $queryBuilder, // QueryBuilder
                $request->query->getInt('page', 1), // Numéro de la page
                5 // Limite par page
            );

<<<<<<< HEAD:src/Controller/Admin/Thematic/ThematicController.php
            $thematiques = $pagination->getItems();
            foreach ($thematiques as $thematique) {
                $exercisesCount = count($thematique->getExercises());
                $thematique->exercisesCount = $exercisesCount;

                // Accéder à la propriété course via le getter de l'entité Thematic
                $courseLinked = $thematique->getCourse();
                $thematique->courseLinked = $courseLinked;
            }
    
=======
>>>>>>> f909c23d1bb4528a218bc41fa530dde00eb43e55:src/Controller/Admin/ThematicController.php
            // Renvoyez le résultat à votre template, avec la pagination et le terme de recherche
            return $this->render('admin/thematic/index.html.twig', [
                'pagination' => $pagination,
                'searchTerm' => $searchTerm,
            ]);
        }


    }
