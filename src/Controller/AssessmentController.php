<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use LearnositySdk\Request\Init;
use LearnositySdk\Utils\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function foo\func;

class AssessmentController extends AbstractController
{
    /**
     * @Route("/assessment/quiz/{quizId}", name="assess_quiz")
     * @ParamConverter("quiz", options={"id"="quizId"}, class="App\Entity\Quiz")
     * @param Quiz $quiz
     * @return Response
     */
    public function assessQuiz(Quiz $quiz)
    {
        return $this->render('assessment/index.html.twig', [
            'controller_name' => 'AssessmentController',
            'quiz' => $quiz
        ]);
    }
}
