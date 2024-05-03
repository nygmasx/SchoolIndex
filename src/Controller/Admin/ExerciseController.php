<?php

namespace App\Controller\Admin;

use App\Entity\Exercise;
use App\Entity\Origin;
use App\Entity\Skill;
use App\Entity\Thematic;
use App\Form\Exercise\ExerciseFileType;
use App\Form\Exercise\ExerciseGeneralInformationType;
use App\Form\Exercise\ExerciseSourceType;
use App\Form\ExerciseType;
use App\Repository\SkillRepository;
use App\Repository\ThematicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;


final class ExerciseController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ThematicRepository     $thematicRepository,
        private readonly SkillRepository        $skillRepository,
    )
    {
    }

    #[Route('/exercise', name: 'app_admin_exercise')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search', '');

        $exerciseRepository = $entityManager->getRepository(Exercise::class);

        // Utilisez une méthode de recherche personnalisée si un terme de recherche est spécifié
        if ($searchTerm) {
            $exercisesQuery = $exerciseRepository->getExerciseSearchQueryBuilder($searchTerm);
        } else {
            $exercisesQuery = $exerciseRepository->findAll();
        }

        // Paginer les résultats de la requête
        $exercises = $paginator->paginate(
            $exercisesQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de la page
            10 // Nombre d'éléments par page
        );

        return $this->render('admin/exercise/index.html.twig', [
            'exercises' => $exercises,
        ]);
    }

    #[Route('/exercice/delete/{id}', name: 'app_admin_exercise_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Exercise $exercise): Response
    {
        // Création du formulaire de confirmation
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($exercise);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'exercice a été supprimée avec succès.');

            return $this->redirectToRoute('app_admin_exercise');
        }

        return $this->render('admin/exercise/delete.html.twig', [
            'exercise' => $exercise,
            'confirmationForm' => $form->createView(),
        ]);
    }

}
