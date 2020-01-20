<?php

namespace App\DataFixtures;

use App\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{

    const QUIZ_REFERENCE_PREFIX = "quiz_";
    public function load(ObjectManager $manager)
    {
        // Create 5 quiz
        for($i = 0; $i < 5; ++$i) {
            $quiz = new Quiz();
            $quiz->setName("quiz_fixture_" . $i);
            $manager->persist($quiz);
            $this->addReference(self::QUIZ_REFERENCE_PREFIX . $i, $quiz);
        }

        $manager->flush();
    }
}
