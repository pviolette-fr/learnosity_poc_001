<?php


namespace App\Service\Scoring;


class QuestionScoringServicesFactory
{
    private $services = [
        'mcq' => QuestionMcqScoringService::class,
        'association' => AssociationScoringService::class,
    ];


    public function getScoringService($questionType): QuestionScoringServiceInterface
    {
        $class = $this->services[$questionType];
        if(!empty($class)) {
            return new $class();
        }
        return null;
    }
}
