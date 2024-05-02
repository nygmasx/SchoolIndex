<?php

namespace App\Controller\Contributors;

use App\Entity\File;
use App\Form\FileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FileController extends AbstractController
{
    #[Route('/file', name: 'app_file')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($file);
            $entityManager->flush();
        }

        return $this->render('file/index.html.twig', [
            'form' => $form,
        ]);
    }
}
