<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttemptQuestionAnswerRepository")
 */
class AttemptQuestionAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuizAttempt", inversedBy="attemptQuestionAnswers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizAttempt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getQuizAttempt(): ?QuizAttempt
    {
        return $this->quizAttempt;
    }

    public function setQuizAttempt(?QuizAttempt $quizAttempt): self
    {
        $this->quizAttempt = $quizAttempt;

        return $this;
    }
}
