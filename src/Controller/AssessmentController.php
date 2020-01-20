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
     * @Route("/assessment", name="assessment", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        // Learnosity demo value
        $consumerKey = 'yis0TYCu7U9V4o7M';
//        $consumerSecret = 'superfragilisticexpialidocious';
        $consumerSecret = "74c5fd430cf1242a527f6223aebd42d30464be22";
        $security = array(
            "consumer_key" => $consumerKey,
            'user_id' => Uuid::generate(),
            'domain' => $_SERVER['SERVER_NAME'],
        );

        $request = [
            'type' => 'local_practice',
            'state' => 'initial',
            'questions' => [
                [
                    'response_id' => '60005',
                    'type' => 'association',
                    'stimulus' => 'Match the cities to the parent nation.',
                    'stimulus_list' => ['London', 'Dublin', 'Paris', 'Sydney'],
                    'possible_responses' => ['Australia', 'France', 'Ireland', 'England'],
                    'validation' => [
                        'valid_responses' => [
                            ['England'], ['Ireland'], ['France'], ['Australia']
                        ]
                    ]
                ],
                [
                    'response_id' => '60006',
                    'type' => 'association',
                    'stimulus' => 'Match the cities to the parent nation.',
                    'stimulus_list' => ['Madrid', 'Dublin', 'Helsinki', 'Roma'],
                    'possible_responses' => ['Italy', 'Finland', 'Ireland', 'Spain'],
                    'validation' => [
                        'valid_responses' => [
                            ['Spain'], ['Ireland'], ['Finland'], ['Italy']
                        ]
                    ]
                ]
            ]
        ];

        $learnosityInit = new Init(
            'questions',
            $security,
            $consumerSecret,
            $request
        );

        $generatedRequest = $learnosityInit->generate();
        return $this->render('assessment/index.html.twig', [
            'controller_name' => 'AssessmentController',
            'learnosityRequest' => $generatedRequest
        ]);
    }

    /**
     * @Route("/assessment/quiz/{quizId}", name="assess_quiz")
     * @ParamConverter("quiz", options={"id"="quizId"}, class="App\Entity\Quiz")
     * @param Quiz $quiz
     * @return Response
     */
    public function assessQuiz(Quiz $quiz)
    {
        // Learnosity demo value
        $consumerKey = 'yis0TYCu7U9V4o7M';
//        $consumerSecret = 'superfragilisticexpialidocious';
        $consumerSecret = "74c5fd430cf1242a527f6223aebd42d30464be22";
        $security = array(
            "consumer_key" => $consumerKey,
            'user_id' => Uuid::generate(),
            'domain' => $_SERVER['SERVER_NAME'],
        );

        $request = [// TODO
            'type' => 'local_practice',
            'state' => 'initial',
            'questions' => $quiz->getQuestions()->map(function (Question $question) {
                $base = $question->getConfig();
                return array_merge($base, [
                    'response_id' => $question->getId()
                ]);
            })
        ];

        $learnosityInit = new Init(
            'questions',
            $security,
            $consumerSecret,
            $request
        );

        $generatedRequest = $learnosityInit->generate();
        return $this->render('assessment/index.html.twig', [
            'controller_name' => 'AssessmentController',
            'learnosityRequest' => $generatedRequest
        ]);
    }
}
