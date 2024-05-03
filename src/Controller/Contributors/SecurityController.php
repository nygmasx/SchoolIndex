<?php

namespace App\Controller\Contributors;

use App\Form\ResetPasswordType;
use App\Form\ResettingRequestType;
use App\Repository\UserRepository;
use App\Service\JWT;
use App\Service\MailerBuilder;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct
    (
        private readonly UserRepository         $userRepository,
        private readonly JWT                    $jwt,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home', [], 303);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/resetting-request', name: 'app_resetting_request')]
    public function resettingRequest(SendEmailService $mail, Request $request,): Response
    {
        $form = $this->createForm(ResettingRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneByEmail($form->get('email')->getData());
            if (null === $user) {
                $this->addFlash('danger', 'Un problème est survenu');
                return $this->redirectToRoute('app_login');
            } else {
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'H256'
                ];

                $payload = [
                    'user_id' => $user->getId(),
                ];

                $token = $this->jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $mail->send(
                    ' contact@lyceestvincent.net',
                    $user->getEmail(),
                    'SchoolIndex - ' . $user->getFirstName() . 'demande un changement de mot de passe',
                    'resetting_request',
                    compact('user', 'url')
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('security/resetting_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
