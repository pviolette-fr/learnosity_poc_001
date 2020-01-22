<?php


namespace App\Service\Scoring;


use App\Entity\AttemptQuestionAnswer;

interface QuestionScoringServiceInterface
{
    function scoreAttempt(AttemptQuestionAnswer $attemptQuestionAnswer);
}
