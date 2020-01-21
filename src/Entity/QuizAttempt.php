<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizAttemptRepository")
 */
class QuizAttempt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Learner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $learner;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSubmitted;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $submittedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AttemptQuestionAnswer", mappedBy="quizAttempt", orphanRemoval=true)
     */
    private $attemptQuestionAnswers;

    public function __construct()
    {
        $this->attemptQuestionAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getLearner(): ?Learner
    {
        return $this->learner;
    }

    public function setLearner(?Learner $learner): self
    {
        $this->learner = $learner;

        return $this;
    }

    public function getIsSubmitted(): ?bool
    {
        return $this->isSubmitted;
    }

    public function setIsSubmitted(bool $isSubmitted): self
    {
        $this->isSubmitted = $isSubmitted;

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

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeInterface
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?\DateTimeInterface $submittedAt): self
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    /**
     * @return Collection|AttemptQuestionAnswer[]
     */
    public function getAttemptQuestionAnswers(): Collection
    {
        return $this->attemptQuestionAnswers;
    }

    public function addAttemptQuestionAnswer(AttemptQuestionAnswer $attemptQuestionAnswer): self
    {
        if (!$this->attemptQuestionAnswers->contains($attemptQuestionAnswer)) {
            $this->attemptQuestionAnswers[] = $attemptQuestionAnswer;
            $attemptQuestionAnswer->setQuizAttempt($this);
        }

        return $this;
    }

    public function removeAttemptQuestionAnswer(AttemptQuestionAnswer $attemptQuestionAnswer): self
    {
        if ($this->attemptQuestionAnswers->contains($attemptQuestionAnswer)) {
            $this->attemptQuestionAnswers->removeElement($attemptQuestionAnswer);
            // set the owning side to null (unless already changed)
            if ($attemptQuestionAnswer->getQuizAttempt() === $this) {
                $attemptQuestionAnswer->setQuizAttempt(null);
            }
        }

        return $this;
    }
}
