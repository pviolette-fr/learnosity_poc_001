<?php


namespace App\Controller\API;


use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {

        $this->serializer = $serializer;
        $this->validator = $validator;
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
