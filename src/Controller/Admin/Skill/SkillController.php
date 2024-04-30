<?php

namespace App\Controller\Admin\Skill;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Knp\Component\Pager\PaginatorInterface;

class SkillController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly PaginatorInterface $paginator, 
        private readonly skillRepository $skillRepository, 
    ) {}

    #[Route('/compétences/new', name: 'new_skill')]
    public function addskill(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    

            $this->entityManager->persist($skill);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_skill');
        }

        return $this->render('admin/skill/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/compétences/delete/{id}', name: 'skill_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, skill $skill, AuthorizationCheckerInterface $authChecker, EntityManagerInterface $entityManager): Response
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
            $entityManager->remove($skill);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

            return $this->redirectToRoute('app_skill');
        }

        return $this->render('admin/skill/delete.html.twig', [
            'skill' => $skill,
            'confirmationForm' => $form->createView(),
        ]);
    }


    #[Route('/compétences/edit/{id}', name: 'edit_skill')]
    public function editskill(skill $skill, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }
        
        $form = $this->createForm(SkillType::class, $skill, [
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

            return $this->redirectToRoute('app_skill');
        }

        return $this->render('admin/skill/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/compétences', name: 'app_skill')]
    public function index(Request $request): Response
    {
        // Capture le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search', '');

        // Utilisez la méthode modifiée pour obtenir un QueryBuilder basé sur le terme de recherche
        $queryBuilder = $this->skillRepository->getSearchQueryBuilder($searchTerm);

        // Paginez le résultat du QueryBuilder
        $pagination = $this->paginator->paginate(
            $queryBuilder, // QueryBuilder
            $request->query->getInt('page', 1), // Numéro de la page
            4 // Limite par page
        );

        // Renvoyez le résultat à votre template, avec la pagination et le terme de recherche
        return $this->render('admin/skill/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
        ]);
    }
}
