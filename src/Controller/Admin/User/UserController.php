<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Form\UserType;
use App\Security\LoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserAuthenticatorInterface $userAuthenticator,
        private LoginAuthenticator $loginAuthenticator,
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route('/utilisateurs/create', name: 'new_user')]
    public function addUser(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode le mot de passe
            $user->setPassword(

                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )

            );

            // Récupère les rôles depuis le formulaire
            $role = $form->get('role')->getData();
            $roles = $user->getRoles();
            // Assure que les rôles sont un tableau
            if (empty($roles) || !is_array($roles)) {
                $roles = [$role];
            }
            // Assure que les rôles sont uniques
            $roles = array_unique($roles);
            
            // Attribue les rôles à l'utilisateur
            $user->setRoles($roles);


            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contributor');
        }

        return $this->render('admin/contributor/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateurs/delete/{id}', name: 'user_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, User $user, AuthorizationCheckerInterface $authChecker, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }
    
        // Création du formulaire de confirmation de suppression

            $form = $this->createForm(UserType::class, $user);

            // Création du formulaire de confirmation
            $form = $this->createFormBuilder()
                ->getForm();
    
            $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer tous les exercices associés à l'utilisateur
            $exercises = $user->getExercises();
    
            // Supprimer chaque exercice associé à l'utilisateur
            foreach ($exercises as $exercise) {
                $entityManager->remove($exercise);
            }
    
            // Ensuite, supprimer l'utilisateur lui-même
            $entityManager->remove($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'L\'utilisateur et ses exercices associés ont été supprimés avec succès.');
    
            return $this->redirectToRoute('app_contributor');
        }
    
        return $this->render('admin/contributor/delete.html.twig', [
            'user' => $user,
            'confirmationForm' => $form->createView(),
        ]);
    }
        

    #[Route('/utilisateurs/edit/{id}', name: 'edit_user')]
    public function editUser(User $user, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        // Vérifie si l'utilisateur a le rôle admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }
        
        $form = $this->createForm(UserType::class, $user, [
            'require_password' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

            return $this->redirectToRoute('app_contributor');
        }

        return $this->render('admin/contributor/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

