<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClassroomType;



class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'avez pas accès à cette page.");
        }

        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $classrooms = $entityManager->getRepository(Classroom::class)->findBySearchTerm($searchTerm);
        } else {
            $classrooms = $entityManager->getRepository(Classroom::class)->findAll();
        }

        // Fetch the count of related exercises for each classroom
        $classroomData = [];
        foreach ($classrooms as $classroom) {
            $classroomData[] = [
                'classroom' => $classroom,
                'exerciseCount' => $classroom->getNumberOfExercises(),
            ];
        }

        return $this->render('classroom/index.html.twig', [
            'classrooms' => $classroomData,
        ]);
    }

    #[Route('/classroom/create', name: 'add_classroom')]
    public function addClassroom(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créez un nouvel objet Classroom
        $classroom = new Classroom();
    
        // Créez un formulaire pour traiter les données
        $form = $this->createForm(ClassroomType::class, $classroom); // Modifier ici
    
        // Gérez la soumission du formulaire
        $form->handleRequest($request);
    
        // Vérifiez si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez la nouvelle classe en base de données
            $entityManager->persist($classroom);
            $entityManager->flush();
    
            // Redirigez l'utilisateur vers une page appropriée (par exemple, la liste des classes)
            return $this->redirectToRoute('app_classroom');
        }
    
        // Affichez le formulaire pour permettre à l'utilisateur d'ajouter une nouvelle classe
        return $this->render('classroom/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/classroom/edit/{id}', name: 'edit_classroom')]
    public function editClassroom(Request $request, EntityManagerInterface $entityManager, Classroom $classroom): Response
    {
        // Créez un formulaire pour traiter les données de la classe
        $form = $this->createForm(ClassroomType::class, $classroom);
        
        // Gérez la soumission du formulaire
        $form->handleRequest($request);
        
        // Vérifiez si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez les modifications de la classe en base de données
            $entityManager->flush();
        
            // Redirigez l'utilisateur vers une page appropriée (par exemple, la liste des classes)
            return $this->redirectToRoute('app_classroom');
        }
        
        // Affichez le formulaire pour permettre à l'utilisateur de modifier la classe
        return $this->render('classroom/edit.html.twig', [
            'form' => $form->createView(),
            'classroom' => $classroom, // Assurez-vous de transmettre la variable classroom au modèle
        ]);
    }
        
}
