<?php

namespace App\Controller\Admin;

use App\Entity\Exercise;
use App\Entity\Origin;
use App\Entity\Skill;
use App\Entity\Thematic;
use App\Form\Exercise\ExerciseFileType;
use App\Form\Exercise\ExerciseGeneralInformationType;
use App\Form\Exercise\ExerciseSourceType;
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
            5 // Nombre d'éléments par page
        );

        return $this->render('admin/exercise/index.html.twig', [
            'exercises' => $exercises,
        ]);
    }

    #[Route('/create/general', name: 'exercise_admin_create_general')]
    public function createStep1(Request $request, SessionInterface $session): Response
    {
        $form = $this->createForm(ExerciseGeneralInformationType::class);
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

            $session->set('step1_data', $formData);
            return $this->redirectToRoute('exercise_create_sources');
        }

        return $this->render('/admin/exercise/step-general.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/general/{id}', name: 'exercise_admin_edit_general')]
    public function editStep1(Request $request, Exercise $exercise, SessionInterface $session): Response
    {
        $form = $this->createForm(ExerciseGeneralInformationType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            $session->set('edit_step1_data', $exercise);
            return $this->redirectToRoute('exercise_admin_edit_sources', ['id' => $exercise->getId()]);
        }

        return $this->render('/admin/exercise/step-general.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/create/sources', name: 'exercise_admin_create_sources')]
    public function createStep2(Request $request, SessionInterface $session): Response
    {
        $form = $this->createForm(ExerciseSourceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('step2_data', $form->getData());
            return $this->redirectToRoute('exercise_create_files');
        }

        return $this->render('/contributors/exercise/step-sources.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/sources/{id}', name: 'exercise_admin_edit_sources')]
    public function editStep2(Request $request, SessionInterface $session, Exercise $exercise): Response
    {
        $form = $this->createForm(ExerciseSourceType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('edit_step2_data', $exercise);
            return $this->redirectToRoute('exercise_admin_edit_files', ['id' => $exercise->getId()]);
        }

        return $this->render('/contributors/exercise/step-sources.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @throws ORMException
     */
    #[Route('/create/files', name: 'exercise_admin_create_files')]
    public function createStep3(Request $request, SessionInterface $session): Response
    {
        $step1Data = $session->get('step1_data'); // Data from previous steps
        $step2Data = $session->get('step2_data'); // Data from previous steps
        $form = $this->createForm(ExerciseFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $step3Data = $form->getData();
            $exercise = new Exercise();
            $exercise->setName($step1Data->getName());
            $exercise->setChapter($step1Data->getChapter());
            $exercise->setCourse($step1Data->getCourse()); // Verify this line, it may also cause errors if getCourse returns null or doesn't expect setName()
            $exercise->setClassroom($step1Data->getClassroom());
            $exercise->setThematic($step1Data->getThematic());
            $exercise->setKeywords($step1Data->getKeywords());
            $exercise->setDifficulty($step1Data->getDifficulty());
            $exercise->setDuration($step1Data->getDuration());
            // Correctly add each Skill to the Exercise
            foreach ($step1Data->getSkills() as $skill) {
                $exercise->addSkill($skill);
            }
            $exercise->setOrigin($step2Data->getOrigin());
            $exercise->setOriginInformation($step2Data->getOriginInformation());
            $exercise->setProposedByType($step2Data->getProposedByType());
            $exercise->setProposedByFirstName($step2Data->getProposedByFirstName());
            $exercise->setProposedByLastName($step2Data->getProposedByLastName());
            $exercise->setFirstFile($step3Data->getFirstFile());
            $exercise->setSecondFile($step3Data->getSecondFile());

            $exercise->setCreatedAt(new \DateTimeImmutable());
            $exercise->setCreatedBy($this->getUser());

            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/contributors/exercise/step-files.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/files/{id}', name: 'exercise_admin_edit_files')]
    public function editStep3(Request $request, SessionInterface $session, Exercise $exercise): Response
    {
        $form = $this->createForm(ExerciseFileType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file uploads here, similar to your create function
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/contributors/exercise/step-files.html.twig', [
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