<?php


namespace App\Service\Scoring;


use App\Entity\AttemptQuestionAnswer;

class AssociationScoringService implements QuestionScoringServiceInterface
{

    function scoreAttempt(AttemptQuestionAnswer $attemptQuestionAnswer)
    {
        // TODO: Implement scoreAttempt() method.
        $attemptQuestionAnswer->setScore(1);
        return 1;
    }
}
