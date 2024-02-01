<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentAnswerController extends AbstractController
{
    #[Route('/student/answer', name: 'app_student_answer')]
    public function index(): Response
    {
        return $this->render('student_answer/index.html.twig', [
            'controller_name' => 'StudentAnswerController',
        ]);
    }
}
