<?php

namespace App\Controller\Contributors;

use App\DataTransferObject\ExerciseFileDto;
use App\DataTransferObject\ExerciseGeneralDto;
use App\DataTransferObject\ExerciseSourceDto;
use App\Entity\Exercise;
use App\Entity\User;
use App\Factory\ExerciseFactory;
use App\Form\Exercise\ExerciseFileType;
use App\Form\Exercise\ExerciseGeneralInformationType;
use App\Form\Exercise\ExerciseSourceType;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Self_;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/submit')]
final class ExerciseController extends AbstractController
{
    private const EXERCISE_CREATE_STEP_ONE = "general";
    private const EXERCISE_CREATE_STEP_TWO = "sources";
    private const EXERCISE_CREATE_STEP_THREE = "files";

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExerciseRepository     $exerciseRepository,
        private readonly ExerciseFactory        $exerciseFactory,
        private readonly RequestStack           $request,
    )
    {
    }

    #[Route('/exercise/create/{step}', name: 'app_submit_exercise')]
    public function create(string $step, Request $request, #[CurrentUser] User $currentUser): Response
    {
        $form = match ($step) {
            self::EXERCISE_CREATE_STEP_ONE => $this->renderExerciseCreateFormStepOne(),
            self::EXERCISE_CREATE_STEP_TWO => $this->renderExerciseCreateFormStepTwo(),
            self::EXERCISE_CREATE_STEP_THREE => $this->renderExerciseCreateFormStepThree(),
            default => $this->redirectToRoute('app_submit_exercise', ['step' => self::EXERCISE_CREATE_STEP_ONE])
        };

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return match (true) {
                $step === self::EXERCISE_CREATE_STEP_ONE => $this->handleExerciseFormStepOne($form),
                $step === self::EXERCISE_CREATE_STEP_TWO => $this->handleExerciseFormStepTwo($form),
                $step === self::EXERCISE_CREATE_STEP_THREE => $this->handleExerciseFormStepThree($form, $currentUser),
                default => $this->redirectToRoute('app_submit_exercise', ['step' => self::EXERCISE_CREATE_STEP_ONE])
            };
        }

        return $this->render(sprintf('/contributors/exercise/step-%s.html.twig', $step), [
            'form' => $form,
            'data' => $form->getData()
        ]);

    }

    private function renderExerciseCreateFormStepOne(): FormInterface
    {
        $exerciseGeneralDto = $this->request->getSession()->get('exercise-form-step-one');

        if (!$exerciseGeneralDto instanceof ExerciseGeneralDto) {
            $exerciseGeneralDto = new ExerciseGeneralDto();
        }

        return $this->createForm(ExerciseGeneralInformationType::class, $exerciseGeneralDto);
    }

    private function renderExerciseCreateFormStepTwo(): FormInterface
    {
        $exerciseSourcesDto = $this->request->getSession()->get('exercise-form-step-two');

        if (!$exerciseSourcesDto instanceof ExerciseSourceDto) {
            $exerciseSourcesDto = new ExerciseSourceDto();
        }

        return $this->createForm(ExerciseSourceType::class, $exerciseSourcesDto);
    }

    private function renderExerciseCreateFormStepThree(): FormInterface
    {
        $exerciseFilesDto = $this->request->getSession()->get('exercise-form-step-three');

        if (!$exerciseFilesDto instanceof ExerciseFileDto) {
            $exerciseFilesDto = new ExerciseFileDto();
        }

        return $this->createForm(ExerciseFileType::class, $exerciseFilesDto);
    }

    private function handleExerciseFormStepOne(FormInterface $form): Response
    {
        $this->request->getSession()->set('exercise-form-step-one', $form->getData());
        return $this->redirectToRoute('app_submit_exercise', ['step' => self::EXERCISE_CREATE_STEP_TWO]);
    }

    private function handleExerciseFormStepTwo(FormInterface $form,): Response
    {
        $this->request->getSession()->set('exercise-form-step-two', $form->getData());
        return $this->redirectToRoute('app_submit_exercise', ['step' => self::EXERCISE_CREATE_STEP_THREE]);
    }

    private function handleExerciseFormStepThree(FormInterface $form, User $user): Response
    {
        /** @var ExerciseGeneralDto $exerciseGeneralDto */
        $exerciseGeneralDto = $this->request->getSession()->get('exercise-form-step-one');
        /** @var ExerciseSourceDto $exerciseSourcesDto */
        $exerciseSourcesDto = $this->request->getSession()->get('exercise-form-step-two');

        $exercise = $this->exerciseFactory->createFormDtos(
            exerciseGeneralDto: $exerciseGeneralDto,
            exerciseSourceDto: $exerciseSourcesDto,
            exerciseFileDto: $form->getData(),
            createdBy: $user
        );

        $this->exerciseRepository->save($exercise, true);

        $this->request->getSession()->set('exercise-form-step-one', null);
        $this->request->getSession()->set('exercise-form-step-two', null);

        return $this->redirectToRoute('app_submit_exercise', ['id' => $exercise->getId()]);
    }
}
