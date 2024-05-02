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

    #[Route('/exercise/create', name: 'admin_exercise_create')]
    public function createStep1(Request $request, SessionInterface $session): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $formArray = $request->request->all()['form'] ?? [];

            if (isset($formArray['thematic'])) {
                $thematicId = $formArray['thematic'];
                $thematic = $this->thematicRepository->find($thematicId);
                if ($thematic) {
                    $formData->setThematic($thematic);
                } else {
                    // Handle error if the thematic is not found
                    $this->addFlash('error', 'Selected thematic not found');
                    return $this->redirectToRoute('exercise_create_general');  // Redirect or handle as needed
                }
            }

            // Handling skills in a similar way, ensuring entities are fetched and set
            if (isset($formArray['skills'])) {
                foreach ($formArray['skills'] as $skillId) {
                    $skill = $this->skillRepository->find($skillId);
                    if ($skill) {
                        $formData->addSkill($skill);
                    } else {
                        // Optionally handle the case where skill is not found
                    }
                }
            }

            $this->entityManager->persist($exercise);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_exercise');
        }

        return $this->render('/admin/exercise/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/update-thematic-field', name: 'update_admin_thematic_field', methods: ['POST'])]
    public function updateThematicField(Request $request, ThematicRepository $thematicRepository, SessionInterface $session): Response
    {
        $courseId = $request->request->get('course_id');
        // Assuming you have a method to get thematics by course
        $thematics = $thematicRepository->findBy(['course' => $courseId]);

        $form = $this->createFormBuilder()
            ->add('thematic', EntityType::class, [
                'class' => Thematic::class,
                'choices' => $thematics,
                'choice_label' => 'name',
                'label' => 'Thematique :',
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $selectedThematic = $form->get('thematic')->getData();
                $session->set('selected_thematic', $selectedThematic);
            }
        }

        return $this->render('admin/exercise/forms/thematic.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update-skills-field', name: 'update_admin_skills_field', methods: ['POST'])]
    public function updateSkillsField(Request $request, SkillRepository $skillRepository, SessionInterface $session): Response
    {
        $courseId = $request->request->get('course_id');
        // Assuming you have a method to get thematics by course
        $skills = $skillRepository->findBy(['course' => $courseId]);

        $form = $this->createFormBuilder()
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choices' => $skills,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedThematic = $form->get('skills')->getData();
            $session->set('selected_skills', $selectedThematic);
        }

        return $this->render('admin/exercise/forms/skills.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
