<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\StudentAnswer;
use App\Entity\Subject;
use App\Form\StudentAnswerType;
use App\Form\SubjectType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subject')]
class SubjectController extends AbstractController
{
    #[Route('/', name: 'app_subject_index', methods: ['GET'])]
    public function index(SubjectRepository $subjectRepository): Response
    {
        return $this->render('subject/index.html.twig', [
            'subjects' => $subjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subject_show', methods: ['GET'])]
    public function show(Subject $subject): Response
    {
       return $this->render('subject/show.html.twig', [
           'subject' => $subject,
       ]);
    }

    #[Route('/{id}/edit', name: 'app_subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subject $subject, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $subject, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/quizzes', name: 'app_subject_quizzes')]
    public function quizzes(Subject $subject,
                       SubjectRepository $subjectRepository,
                        QuizRepository $quizRepository):Response
    {

       $quizzes = $quizRepository->findBy(
          ['subjectFK' => $subject],);

       return $this->render('subject/quizzes.html.twig', [
            'quizzes' => $quizzes,
            'subject' => $subject,
       ]);
   }

    #[Route('/{id}/quizzes/{quiz_id}', name: 'app_subject_quiz')]
    public function takeQuiz(
        Subject $subject,
        #[MapEntity(expr: 'repository.find(quiz_id)')]
        Quiz $quiz,
        Request $request,
        EntityManagerInterface $entityManager,
        SubjectRepository $subjectRepository,
        QuestionRepository $questionRepo,

    ): Response {
        $questions = $questionRepo->findBy(['quiz' => $quiz]);
        $studentAnswer = new StudentAnswer();
        $saForm = $this->createForm(StudentAnswerType::class, $studentAnswer);
        $saForm->handleRequest($request);
        if ($saForm->isSubmitted()  && $saForm->isValid()) {
            $entityManager->persist($studentAnswer);
        }
        $entityManager->flush();

        $question = $questionRepo->findOneBy(['quiz' => $quiz]);
        $correctAnswer = $question->getAnswer();
        $stAnswer =  $saForm->get('answer')->getData();
        if ($stAnswer === $correctAnswer) {
            $this->addFlash('correct', "Correct !");
        }

        else {
            $this->addFlash('oops', "Oups ! ");
        }
        return $this->render('subject/quiz.html.twig', [
            'questions' => $questions,
            'quiz' => $quiz,
            'studentAnswer' => $studentAnswer,
            'question' => $question,
            'saForm' => $saForm,
            'correctAnswer' => $correctAnswer,
            'stAnswer' => $stAnswer]);

    }

}
