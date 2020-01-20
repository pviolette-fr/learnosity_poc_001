<?php

namespace App\Controller;

use App\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);

        $quizzes = $repository->findAll();

        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
            'quizzes' => $quizzes
        ]);
    }

    /**
     * @Route(
     *     "/quiz/{id}",
     *     methods={"GET"},
     *     name="quiz_editor"
     * )
     * @ParamConverter("quiz", class="App\Entity\Quiz")
     * @param Quiz $quiz
     * @return Response
     */
    public function edit(Quiz $quiz)
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);


        return $this->render('quiz/edit.html.twig', [
            'quizId' => $quiz->getId()
        ]);
    }
}
