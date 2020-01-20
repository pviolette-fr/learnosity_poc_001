<?php

namespace App\Controller\API;

use App\Entity\Question;
use App\Entity\Quiz;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class QuestionController
 * @package App\Controller\API
 */
class QuestionController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * QuestionController constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }


    /**
     * @Route(
     *     "/api/quiz/{quizId}/question/",
     *     methods={"GET"},
     *     name="api_quiz_questions_index",
     *     requirements={"quizId"="\d+"}
     * )
     * @ParamConverter("quiz", options={"id"="quizId"}, class="App\Entity\Quiz")
     * @param Quiz $quiz
     * @return Response
     */
    public function index(Quiz $quiz)
    {
        $questions = $quiz->getQuestions();

        $httpCode = $questions->isEmpty() ?
            Response::HTTP_NO_CONTENT
            : Response::HTTP_OK;

        $data = $this->serializer->serialize(
            $questions,
            'json',
            SerializationContext::create()->setGroups(["list"])
        );
        return new Response($data, $httpCode, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route(
     *     "/api/quiz/{quizId}/question/{id}",
     *     methods={"GET"},
     *     name="api_quiz_questions_show",
     *     requirements={"quizId"="\d+", "id"="\d+"}
     *
     * )
     * @ParamConverter("quiz", options={"id"="quizId"}, class="App\Entity\Quiz")
     * @ParamConverter("question", class="App\Entity\Question")
     * @param Quiz $quiz
     * @param Question $question
     * @return Response
     */
    public function show(Quiz $quiz, Question $question) {
        $data = $this->serializer->serialize(
            $question,
            'json',
            SerializationContext::create()->setGroups(["details"])
        );

        $response = new Response($data, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/api/quiz/{quizId}/question/",
     *     methods={"POST"},
     *     name="api_quiz_questions_create",
     *     requirements={"quizId"="\d+"}
     * )
     * @ParamConverter("quiz", options={"id"="quizId"}, class="App\Entity\Quiz")
     * @param Quiz $quiz
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function create(Quiz $quiz, Request $request) {
        $data = $request->getContent();
        /** @var Question $question */
        $question = $this->serializer->deserialize($data, Question::class, 'json');

        $question->setQuiz($quiz);

        $errors = $this->getErrors($question, ["create"]);
        if($errors !== false) {
            return $errors;
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($question);
        $entityManager->flush();
        $data = $this->serializer->serialize(
            $question,
            'json',
            SerializationContext::create()->setGroups(["list"])
        );

        $response = new Response($data, Response::HTTP_CREATED);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/api/quiz/{quizId}/question/{id}/",
     *     methods={"PUT"},
     *     name="api_quiz_questions_update",
     *     requirements={"quizId"="\d+", "id": "\d+"}
     * )
     * @ParamConverter("question", class="App\Entity\Question")
     * @param Question $question
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function update(Question $question, Request $request) {
        /** @var @var Question $updateData */
        $updateData = $this->serializer->deserialize($request->getContent(), Question::class, 'json');

        $errors = $this->getErrors($updateData, []);
        if($errors !== false) {
            return $errors;
        }

        if(!empty($updateData->getConfig())){
            $question->setConfig($updateData->getConfig());
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($question);
        $entityManager->flush();

        $data = $this->serializer->serialize(
            $question,
            'json',
            SerializationContext::create()->setGroups(["details"])
        );

        $response = new Response($data, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/api/quiz/{quizId}/question/{id}/",
     *     methods={"DELETE"},
     *     name="api_quiz_questions_destroy",
     *     requirements={"quizId"="\d+", "id"="\d+"}
     * )
     * @ParamConverter("question", class="App\Entity\Question")
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question) {
        $entityManager = $this->getDoctrine()
            ->getManager();
        $entityManager->remove($question);
        $entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * TODO refactor
     * Validate the object with constraints and return the errors if presents in a JsonResponse
     *
     * @param $object mixed Object to validate
     * @param $groups array Array of validations groups
     * @return bool|JsonResponse
     */
    protected function getErrors($object, $groups)
    {
        $errors = $this->validator->validate($object, null, $groups);

        if (count($errors) > 0) {
            $displayError = array();
            foreach($errors as $error) {
                $displayError[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse($displayError, Response::HTTP_BAD_REQUEST);
        }
        else {
            return false;
        }
    }

}
