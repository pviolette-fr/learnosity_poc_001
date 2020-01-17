<?php

namespace App\Controller;

use LearnositySdk\Request\Init;
use LearnositySdk\Utils\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AssessmentController extends AbstractController
{
    /**
     * @Route("/assessment", name="assessment")
     */
    public function index()
    {
        // Learnosity demo value
        $consumerKey = 'yis0TYCu7U9V4o7M';
        $consumerSecret = 'superfragilisticexpialidocious';

        $domain = $_SERVER["SERVER_NAME"];
        $security = array(
            "consumer_key" => $consumerKey,
            'user_id' => Uuid::generate(),
            "domain" => $domain
        );

        $request =             [
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
}
