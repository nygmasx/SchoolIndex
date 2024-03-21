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

class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/add/user', name: 'add_user')]
    public function addUser(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Il est plus sûr de récupérer le mot de passe après avoir vérifié que le formulaire est soumis et valide.
            $plainPassword = $form->get('password')->getData();
            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();
            
            // Rediriger vers la page appropriée après l'ajout de l'utilisateur
            return $this->redirectToRoute('app_admin'); // Assurez-vous de changer 'some_route' par la route réelle vers laquelle vous voulez rediriger l'utilisateur.
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function createDeleteConfirmationForm(): \Symfony\Component\Form\FormInterface
    {
        return $this->createFormBuilder()
            ->add('confirm', SubmitType::class, ['label' => 'Confirmer la suppression'])
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
public function editUser(Request $request, EntityManagerInterface $manager, User $user): Response
{
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Si le mot de passe a été modifié
        $plainPassword = $form->get('password')->getData();
        if (!empty($plainPassword)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        $manager->flush();

        $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

        return $this->redirectToRoute('app_admin'); // Rediriger vers la page appropriée
    }

    return $this->render('admin/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
