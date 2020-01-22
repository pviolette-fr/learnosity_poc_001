<?php


namespace App\Service\Scoring;


use App\Entity\AttemptQuestionAnswer;

class QuestionMcqScoringService implements QuestionScoringServiceInterface
{

    public function scoreAttempt(AttemptQuestionAnswer $attemptQuestionAnswer) {
        $question = $attemptQuestionAnswer->getQuestion();

        if($attemptQuestionAnswer->getValueType() !== 'array') {
            return 0;
        }

        if ($question->getType() !== 'mcq') {
            throw new \InvalidArgumentException();
        }

        $validationsRules = $question->getValidationRules();

        if (is_null($validationsRules)) {
            throw new \RuntimeException();
        }

        switch ($validationsRules['scoring_type']) {
            case 'exactMatch':
                $score = $this->scoreExactMatch($attemptQuestionAnswer);
                break;
            default:
                throw new \RuntimeException("Unimplemented scoring type " . $validationsRules['scoring_type']);
        }

        $attemptQuestionAnswer->setScore($score);
        return $score;
    }

    private function scoreExactMatch(AttemptQuestionAnswer $attemptQuestionAnswer) {
        $question = $attemptQuestionAnswer->getQuestion();
        $validationsRules = $question->getValidationRules();

        $answerValue = $attemptQuestionAnswer->getValue();

        $correctAnswer = $validationsRules['valid_response']['value'];

        $isCorrect = empty(array_diff($answerValue, $correctAnswer)) && empty(array_diff($correctAnswer, $answerValue));

        return $isCorrect ? (int) $validationsRules['valid_response']['score'] : 0;
    }

}
