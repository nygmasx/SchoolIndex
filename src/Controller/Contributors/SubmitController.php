<?php

namespace App\Controller\Contributors;

use App\Entity\Exercise;
use App\Form\GeneralExerciseInformationType;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/submit')]
final class SubmitController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExerciseRepository $exerciseRepository,
    )
    {
    }

    #[Route('/exercise', name: 'app_submit_exercise')]
    public function index(Request $request): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(GeneralExerciseInformationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();
        }

        return $this->render('contributors/submit/index.html.twig', [
            'form' => $form,
        ]);
    }
}
