<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Serializer\Type("App\Entity\Question")
     * @Assert\NotNull
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuizAttempt", inversedBy="attemptQuestionAnswers")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Type("App\Entity\QuizAttempt")
     * @Assert\NotNull
     */
    private $quizAttempt;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Serializer\Type("array")
     * @Assert\NotNull
     */
    private $value = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $valueType;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\ReadOnly
     */
    private $maxScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\ReadOnly
     */
    private $score;

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

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValueType(): ?string
    {
        return $this->valueType;
    }

    public function setValueType(string $valueType): self
    {
        $this->valueType = $valueType;

        return $this;
    }

    public function getMaxScore(): ?int
    {
        return $this->maxScore;
    }

    public function setMaxScore(int $maxScore): self
    {
        $this->maxScore = $maxScore;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
