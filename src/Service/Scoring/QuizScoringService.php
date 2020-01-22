<?php


namespace App\Service\Scoring;


use App\Entity\AttemptQuestionAnswer;
use App\Entity\QuizAttempt;
use Doctrine\ORM\EntityManager;

class QuizScoringService
{
    /**
     * @var QuestionScoringServicesFactory
     */
    private $factory;


    /**
     * QuizScoringService constructor.
     * @param QuestionScoringServicesFactory $factory
     */
    public function __construct(QuestionScoringServicesFactory $factory)
    {
        $this->factory = $factory;
    }

    public function scoreQuizAttempt(QuizAttempt $quizAttempt)
    {

        $answers = $quizAttempt->getAttemptQuestionAnswers();

        $score = 0;
        foreach ($answers as $answer) {
            $scoringService = $this->factory->getScoringService($answer->getQuestion()->getType());

            $score += $scoringService->scoreAttempt($answer);
        }

        $quizAttempt->setScore($score);
    }
}
