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
        $configFixtures = $this->getConfigFixtures();

        for ($i = 0; $i < 5; ++$i) {
            /** @var \App\Entity\Quiz $quiz
             * @noinspection PhpFullyQualifiedNameUsageInspection
             */
            $quiz = $this->getReference(QuizFixtures::QUIZ_REFERENCE_PREFIX . $i);

            for ($i = 0; $i < 5; ++$i) {
                $question = new Question();
                $question->setQuiz($quiz);
                $question->setConfig(!empty($configFixtures[$i]) ? $configFixtures[$i] : null);
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

    private function getConfigFixtures() {
        return  [
            [
                'options' =>
                    [
                        0 =>
                            [
                                'label' => '[Choice A]',
                                'value' => '0',
                            ],
                        1 =>
                            [
                                'label' => '[Choice B]',
                                'value' => '1',
                            ],
                        2 =>
                            [
                                'label' => '[Choice C]',
                                'value' => '2',
                            ],
                        3 =>
                            [
                                'label' => '[Choice D]',
                                'value' => '3',
                            ],
                    ],
                'stimulus' => 'Test 1',
                'type' => 'mcq',
                'validation' =>
                    [
                        'scoring_type' => 'exactMatch',
                        'valid_response' =>
                            [
                                'score' => 1,
                                'value' =>
                                    [
                                        0 => '3',
                                        1 => '0',
                                    ],
                            ],
                    ],
                'ui_style' =>
                    [
                        'type' => 'horizontal',
                    ],
                'multiple_responses' => true,
            ]
        ];
    }
}
