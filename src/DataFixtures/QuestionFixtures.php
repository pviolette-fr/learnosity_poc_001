<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 5; ++$i) {
            /** @var \App\Entity\Quiz $quiz
             * @noinspection PhpFullyQualifiedNameUsageInspection
             */
            $quiz = $this->getReference(QuizFixtures::QUIZ_REFERENCE_PREFIX . $i);

            for($i = 0; $i < 5; ++$i) {
                $question = new Question();
                $question->setQuiz($quiz);
                $question->setConfig(new \stdClass());
                $manager->persist($question);
            }
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            QuizFixtures::class
        ];
    }
}
