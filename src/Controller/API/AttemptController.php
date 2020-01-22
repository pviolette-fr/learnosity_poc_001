<?php

namespace App\Controller\API;

use App\Entity\AttemptQuestionAnswer;
use App\Entity\Learner;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizAttempt;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use LearnositySdk\Request\Init;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AttemptController extends ApiController
{
    /**
     * @Route(
     *     "/api/quiz/{quizId}/startAttempt",
     *     methods={"POST"},
     *     name="api_start_attempt"
     * )
     * @ParamConverter("quiz", options={"id"="quizId"}, class="App\Entity\Quiz")
     *
     * @param Quiz $quiz
     * @return Response
     * @throws \Exception
     */
    public function startAttempt(Quiz $quiz)
    {
        $learner = $this->getLearner();

        $attempt = new QuizAttempt();
        $attempt->setLearner($learner);
        $attempt->setQuiz($quiz);
        $attempt->setIsSubmitted(false);
        $attempt->setMaxScore($quiz->getMaxScore());
        $attempt->setStartedAt(new \DateTime());

        $data = [
            'attempt' => $attempt,
            'learnosity_init' => $this->getLearnosityInitObject($attempt)
        ];

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($attempt);
        $entityManager->flush();

        $context = SerializationContext::create()
            ->setGroups([
                "Default",
                'quiz' => [
                    "list"
                ]
            ])->setSerializeNull(true);

        $response = new Response($this->serializer->serialize($data, 'json', $context), Response::HTTP_CREATED);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route(
     *     "/api/attempt/{id}/submit",
     *     methods={"POST"},
     *     name="api_submit_attempt"
     * )
     * @ParamConverter("quizAttempt", class="App\Entity\QuizAttempt")
     * @param QuizAttempt $quizAttempt
     * @param Request $request
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function submitAnswers(QuizAttempt $quizAttempt, Request $request)
    {
        if($quizAttempt->getIsSubmitted()) {
          return new JsonResponse([
              'message' => 'Already Submitted'
          ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $rawData = $request->getContent();
        /** @var AttemptQuestionAnswer[] $answers */
        $answers = $this->serializer->deserialize($rawData, 'ArrayCollection<App\Entity\AttemptQuestionAnswer>', 'json');
        $errors = $this->getErrors($answers, []);
        if($errors !== false) {
            return $errors;
        }

        $entityManager = $this->getDoctrine()->getManager();

        $quizAttempt->setIsSubmitted(true);
        $quizAttempt->setSubmittedAt(new \DateTime());

        foreach ($answers as $answer) {
            $answer->setQuizAttempt($quizAttempt);
            if($answer->getQuestion()->getQuiz()->getId() !== $quizAttempt->getQuiz()->getId()) {
                return new Response('One of this answered question is not part of the attempted quiz', Response::HTTP_BAD_REQUEST);
            }
            $answer->setMaxScore($answer->getQuestion()->getMaxScore());

            $entityManager->persist($answer);
        }
        $entityManager->flush();

        $response = new Response($this->serializer->serialize($answers, 'json'), Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @param QuizAttempt $attempt
     * @return array
     */
    private function getLearnosityInitObject(QuizAttempt $attempt)
    {
        $quiz = $attempt->getQuiz();
        $learner = $attempt->getLearner();

        // Learnosity demo value
        $consumerKey = 'yis0TYCu7U9V4o7M';
        $consumerSecret = "74c5fd430cf1242a527f6223aebd42d30464be22";

        $security = array(
            "consumer_key" => $consumerKey,
            'user_id' => $learner->getId(),
            'domain' => $_SERVER['SERVER_NAME'],
        );

        $request = [
            'type' => 'local_practice',
            'state' => 'initial',
            'questions' => $quiz->getQuestions()
                ->filter(function (Question $question) {
                    return $question->getConfig() !== null;
                })
                ->map(function (Question $question) {
                    $base = $question->getConfig();
                    return array_merge($base, [
                        'response_id' => $question->getId()
                    ]);
                })->toArray()
        ];

        $learnosityInit = new Init(
            'questions',
            $security,
            $consumerSecret,
            $request
        );

        return $learnosityInit->generate(false);
    }

    private function getLearner()
    {
        // Mock function
        $repository = $this->getDoctrine()->getRepository(Learner::class);
        return $repository->findAll()[0];
    }
}
