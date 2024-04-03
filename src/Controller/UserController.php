<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginAuthenticator;


class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/add/user', name: 'add_user')]
    public function addUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');

            

        
            // do anything else you need here, like send an email
        }
        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function createDeleteConfirmationForm(): \Symfony\Component\Form\FormInterface
    {
        return $this->createFormBuilder()
            ->getForm();
    }

    #[Route('/user-delete/{id}', name: 'user_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, EntityManagerInterface $manager, string $id): Response
    {
        $user = $manager->getRepository(User::class)->find($id);

        if (null === $user) {
            throw $this->createNotFoundException('L\'user n\'a pas été trouvé.');
        }

        $form = $this->createDeleteConfirmationForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash('success', 'L\'user a été supprimé avec succès.');

            return $this->redirectToRoute('app_admin');
        }

        // Afficher la vue de confirmation si le formulaire n'a pas été soumis/validé
        return $this->render('admin/delete.html.twig', [
            'user' => $user,
            'confirmationForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/user/{id}', name: 'edit_user')]
public function editUser(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, User $user): Response
{
    // Crée le formulaire en indiquant que le mot de passe n'est pas requis
    // Assurez-vous que votre UserType accepte et gère une option 'require_password'
    $form = $this->createForm(UserType::class, $user, [
        'require_password' => false,
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifie si un nouveau mot de passe a été fourni
        // Assurez-vous que le champ dans votre formulaire s'appelle 'plainPassword' si vous suivez les recommandations précédentes
        $plainPassword = $form->get('plainPassword')->getData();
        if (!empty($plainPassword)) {
            // Hash le nouveau mot de passe avant de le sauvegarder
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        // Persiste les changements dans la base de données
        $manager->flush();

        // Affiche un message de succès
        $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

        // Redirige vers la page de gestion des utilisateurs
        // Assurez-vous que 'app_admin' est le nom correct de la route vers laquelle vous voulez rediriger
        return $this->redirectToRoute('app_admin');
    }

    return $this->render('admin/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

}