<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Groups({"list", "details"})
     * @Serializer\Type("array")
     * @Assert\NotBlank()
     */
    private $config;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"list", "details"})
     * @Assert\NotBlank(
     *     groups={"create"}
     * )
     */
    private $quiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $config
     * @return $this
     */
    public function setConfig($config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return Quiz|null
     */
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    /**
     * @param Quiz|null $quiz
     * @return $this
     */
    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
}
