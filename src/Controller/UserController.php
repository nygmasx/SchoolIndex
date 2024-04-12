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

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
    private readonly UserAuthenticatorInterface $userAuthenticator,
    private readonly LoginAuthenticator $loginAuthenticator,
    private readonly EntityManagerInterface $entityManager,)
    {

    }

    #[Route('/add/user', name: 'add_user')]
    public function addUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contributors');
            // Vous voudrez peut-être ajuster la route 'app_contributors' selon vos besoins
        }

        return $this->render('admin/contributors/add.html.twig', [
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
public function delete(Request $request, string $id): Response
{
    // Utilisation de findBy au lieu de find
    $user = $this->entityManager->getRepository(User::class)->findBy(['id' => $id]);

    // findBy retourne un tableau, donc vérifiez si le tableau est vide
    if (empty($user)) {
        throw $this->createNotFoundException('L\'user n\'a pas été trouvé.');
    }

    // Comme findBy retourne un tableau, prenez le premier élément
    $user = $user[0];

    $form = $this->createDeleteConfirmationForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'user a été supprimé avec succès.');

        return $this->redirectToRoute('app_contributors');
    }

    // Afficher la vue de confirmation si le formulaire n'a pas été soumis/validé
    return $this->render('admin/delete.html.twig', [
        'user' => $user,
        'confirmationForm' => $form->createView(),
    ]);
}


    #[Route('/edit/user/{id}', name: 'edit_user')]
    public function editUser(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'require_password' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Les informations de l\'utilisateur ont été mises à jour.');

            return $this->redirectToRoute('app_contributors');
        }

        return $this->render('admin/contributors/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
