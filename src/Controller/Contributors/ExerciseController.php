<?php

namespace App\Controller\Contributors;

use App\DataTransferObject\ExerciseFileDto;
use App\DataTransferObject\ExerciseGeneralDto;
use App\DataTransferObject\ExerciseSourceDto;
use App\Entity\Exercise;
use App\Entity\File;
use App\Entity\Skill;
use App\Entity\Thematic;
use App\Entity\User;
use App\Factory\ExerciseFactory;
use App\Form\Exercise\ExerciseFileType;
use App\Form\Exercise\ExerciseGeneralInformationType;
use App\Form\Exercise\ExerciseSourceType;
use App\Repository\ExerciseRepository;
use App\Repository\SkillRepository;
use App\Repository\ThematicRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Self_;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/exercise')]
final class ExerciseController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExerciseRepository     $exerciseRepository,
    )
    {
    }

    #[Route('/create/general', name: 'exercise_create_general')]
    public function createStep1(Request $request, SessionInterface $session): Response
    {
        $form = $this->createForm(ExerciseGeneralInformationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('step1_data', $form->getData());
            return $this->redirectToRoute('exercise_create_sources');
        }

        return $this->render('/contributors/exercise/step-general.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create/sources', name: 'exercise_create_sources')]
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

    #[Route('/create/files', name: 'exercise_create_files')]
    public function createStep3(Request $request, SessionInterface $session): Response
    {
        $step1Data = $session->get('step1_data');
        $step2Data = $session->get('step2_data'); // Assumez qu'il a été défini dans step2
        $selectedThematic = $session->get('selected_thematic'); // Récupération de la thématique sélectionnée
        $selectedSkills = $session->get('selected_skills');
        $form = $this->createForm(ExerciseFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $step3Data = $form->getData();
            $exercise = new Exercise();
            $exercise->setName($step1Data->getName());
            $exercise->setChapter($step1Data->getChapter());
            $exercise->getCourse()->setName($step1Data->getCourse()->getName());
            $exercise->setClassroom($step1Data->getClassroom());
            $exercise->setThematic($selectedThematic);
            $exercise->addSkill($selectedSkills);
            $exercise->setKeywords($step1Data->getKeywords());
            $exercise->setDifficulty($step1Data->getDifficulty()->getValue());
            $exercise->setDuration($step1Data->getDuration());
            $exercise->setOrigin($step2Data->getOrigin());
            $exercise->setOriginName($step2Data->getOriginName());
            $exercise->setOriginInformation($step2Data->getOriginInformation());
            $exercise->setProposedByType($step2Data->getProposedByType());
            $exercise->setProposedByFirstName($step2Data->getProposedByFirstName());
            $exercise->setProposedByLastName($step2Data->getProposedByLastName());

            $exercise->setCreatedAt(new \DateTimeImmutable());
            $exercise->setCreatedBy($this->getUser());
            if ($form['exerciseFile']->getData()) {
                $file = new File($form['exerciseFile']->getData());
                $this->entityManager->persist($file);
                $exercise->setExerciseFile($file);
            }
            if ($form['correctionFile']->getData()) {
                $correctionFile = new File($form['correctionFile']->getData());
                $this->entityManager->persist($correctionFile);
                $exercise->setCorrectionFile($correctionFile);
            }
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/contributors/exercise/step-files.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update-thematic-field', name: 'update_thematic_field', methods: ['POST'])]
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
                return new JsonResponse(['success' => true]);
            }
        }

        return $this->render('contributors/exercise/forms/thematic.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update-skills-field', name: 'update_skills_field', methods: ['POST'])]
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

        return $this->render('contributors/exercise/forms/skills.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
