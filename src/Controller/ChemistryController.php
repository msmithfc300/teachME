<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChemistryController extends AbstractController
{
    #[Route('/chemistry/quiz', name: 'app_chemistry')]
    public function index(): Response
    {
        return $this->render('/chemistry/quiz.html.twig', [
            'controller_name' => 'ChemistryController',
        ]);
    }
}
