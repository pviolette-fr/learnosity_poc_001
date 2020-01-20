<?php

namespace App\Controller\API;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class QuizController
 * @package App\Controller\API
 * @Route("/api/quiz", name="api_quiz_")
 */
class QuizController extends AbstractController
{

    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="index"
     * )
     * @param QuizRepository $quizRepository
     * @return Response
     */
    public function index(QuizRepository $quizRepository)
    {
        $quizzes = $quizRepository->findAll();

        $data = $this->serializer->serialize(
            $quizzes,
            'json',
            SerializationContext::create()->setGroups(["list"])
        );

        $code = count($quizzes) > 0 ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
        $response = new Response($data, $code);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/",
     *     methods={"POST"},
     *     name="create",
     * )
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $data = $request->getContent();
        $quiz = $this->serializer->deserialize($data, Quiz::class, 'json');

        $errors = $this->getErrors($quiz, []);
        if($errors !== false) {
            return $errors;
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($quiz);
        $entityManager->flush();
        $data = $this->serializer->serialize(
            $quiz,
            'json',
            SerializationContext::create()->setGroups(["list"])
        );

        $response = new Response($data, Response::HTTP_CREATED);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/{id}/",
     *     methods={"PUT"},
     *     name="update",
     *     requirements={"id"="\d+"}
     * )
     *
     * @ParamConverter("quiz", class="App\Entity\Quiz")
     * @param Request $request
     * @param Quiz $quiz
     * @return JsonResponse|Response
     */
    public function update(Quiz $quiz, Request $request)
    {
        /** @var @var Quiz $updateData */
        $updateData = $this->serializer->deserialize($request->getContent(), Quiz::class, 'json');

        $errors = $this->getErrors($updateData, []);
        if($errors !== false) {
            return $errors;
        }

        if(!empty($updateData->getName())){
            $quiz->setName($updateData->getName());
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($quiz);
        $entityManager->flush();

        $data = $this->serializer->serialize(
            $quiz,
            'json',
            SerializationContext::create()->setGroups(["details"])
        );

        $response = new Response($data, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route(
     *     "/{id}/",
     *     methods={"GET"},
     *     name="show",
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter("quiz", class="App\Entity\Quiz")
     * @param Quiz $quiz
     * @return Response
     */
    public function show(Quiz $quiz)
    {
        $data = $this->serializer->serialize(
            $quiz,
            'json',
            SerializationContext::create()->setGroups(["details"])
        );

        $response = new Response($data, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/{id}/",
     *     methods={"DELETE"},
     *     name="destroy",
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter("quiz", class="App\Entity\Quiz")
     *
     * @param Quiz $quiz
     * @return Response
     */
    public function destroy(Quiz $quiz)
    {
        $entityManager = $this->getDoctrine()
            ->getManager();
        $entityManager->remove($quiz);
        $entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
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
