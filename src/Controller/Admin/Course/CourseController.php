<?php

namespace App\Controller\Admin\Course;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CourseRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Course;
use App\Form\CourseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CourseController extends AbstractController
{

    public function __construct(
        private LoginAuthenticator $loginAuthenticator,
        private EntityManagerInterface $entityManager,
        private CourseRepository $courseRepository,
    ) {}

    #[Route('/matiere', name: 'app_course')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        // Capture le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search', '');
    
        // Utilisez la méthode modifiée pour obtenir un QueryBuilder basé sur le terme de recherche
        $queryBuilder = $this->courseRepository->getSearchQueryBuilder($searchTerm);

        // Paginez le résultat du QueryBuilder
        $pagination = $paginator->paginate(
            $queryBuilder, // QueryBuilder
            $request->query->getInt('page', 1), // Numéro de la page
            5 // Limite par page
        );

        $courses = $pagination->getItems();
        foreach ($courses as $course) {
            $exerciseCount = count($course->getExercises());
            $course->exercisesCount = $exerciseCount;
        }

        return $this->render('admin/course/index.html.twig', [
            'controller_name' => 'CourseController',
            'searchTerm' => $searchTerm,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/matiere/create', name: 'new_course')]
    public function addcourse(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($course);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_course');
        }

        return $this->render('admin/course/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/matiere/delete/{id}', name: 'course_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Course $course, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        $form = $this->createForm(CourseType::class, $course);

        // Création du formulaire de confirmation
        $form = $this->createFormBuilder()
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($course);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

            return $this->redirectToRoute('app_course');
        }

        return $this->render('admin/course/delete.html.twig', [
            'course' => $course,
            'confirmationForm' => $form->createView(),
        ]);
    }

    #[Route('/matiere/edit/{id}', name: 'edit_course')]
    public function editcourse(Course $course, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }
        
        $form = $this->createForm(CourseType::class, $course, [
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Les informations de la matière ont été mises à jour.');

            return $this->redirectToRoute('app_course');
        }

        return $this->render('admin/course/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
