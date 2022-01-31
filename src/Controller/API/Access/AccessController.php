<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 28.12.2021
 * Time: 22:19
*/

namespace App\Controller\API\Access;

use App\Controller\API\User\Exceptions\UserAlreadyExistsException;
use App\Controller\API\User\Exceptions\UserRepeatedPasswordMatchingException;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Modules\VirtualController\VirtualController;
use App\Repository\UserRepository;
use App\Service\UserServices\AuthService\AuthService;
use App\Service\UserServices\AuthService\Exceptions\UserNotFoundException;
use App\Service\UserServices\AuthService\Exceptions\WrongPasswordException;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AccessController extends VirtualController
{
    private UserRepository $userRepository;
    public function __construct(SerializerInterface $serializer, UserRepository $userRepository)
    {
        parent::__construct($serializer);
        $this->userRepository = $userRepository;
    }
    /**
     * @Route (path="/is-logged", methods={"GET", "POST"})
     * @param Request $request
     * @param JWTEncoderInterface $encoder
     * @return JsonResponse
     */
    public function isLogged(Request $request, JWTEncoderInterface $encoder): JsonResponse
    {
        $status = false;
        try {
            $token = $this->getBearer();
            $status = !!$encoder->decode($token);
            $this->responseBuilder->setStatus(Response::HTTP_OK);
        } catch (JWTDecodeFailureException $ex) {
            $this->responseBuilder->addMessage($ex->getMessage())
                ->setStatus(Response::HTTP_UNAUTHORIZED);
        }
        $this->responseBuilder->addPayload(["status" => $status]);
        return $this->responseBuilder->jsonResponse();
    }

    /**
     * @Route (path="/sign-up", methods={"POST"})
     * @param Request $request
     * @param AuthService $authService
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Exception
     */
    public function signUp(Request $request, AuthService $authService, SerializerInterface $serializer): JsonResponse
    {
        $email = $request->get('user_email');
        $password = $request->get('user_password');
        $passwordRepeat = $request->get('user_password_repeat');
        $userFirstName = $request->get('user_first_name');
        $userLastName = $request->get('user_last_name');
        $userBirthDate = $request->get('user_birth_date');
        $userAvatar = $request->get("user_profile_image");


        try {

            $this->userRepository->verifyRepeatedPassword($password, $passwordRepeat);
            $this->userRepository->exists($email);
            $user = new User();
            $user->setEmail($email)
                ->setUserId(Uuid::uuid4()->toString())
                ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                ->setLastLoginAt()
                ->setCreatedAt();

            $user->profile = new UserProfile();
            $user->profile
                ->setUserId($user->getUserId())
                ->setEmail($user->getEmail())
                ->setFirstName($userFirstName)
                ->setLastName($userLastName)
                ->setBirthDay(new DateTime($userBirthDate))
                ->setAvatar($userAvatar)
                ->setCreatedAt();

            $this->userRepository->save($user);

            $user = $authService->authenticate($email, $password);
            return $this->responseBuilder->addObject($user)->objectResponse();

        } catch (
        UserAlreadyExistsException |
        UserRepeatedPasswordMatchingException $e) {
            $this->responseBuilder->addMessage($e->getMessage());
        } catch (ORMException $e) {
            $this->responseBuilder->somethingWentWrong();
        }
        return $this->responseBuilder->jsonResponse();
    }
    /**
     * @Route (path="/sign-in", methods={"POST"})
     * @param Request $request
     * @param AuthService $authService
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function signIn(Request $request, AuthService $authService, SerializerInterface $serializer): JsonResponse
    {
        $email = $request->get('user_email');
        $password = $request->get('user_password');

        try {
            $user = $authService->authenticate($email, $password);
            return $this->responseBuilder->addObject($user)->objectResponse();

        } catch (UserNotFoundException
        |OptimisticLockException
        |NonUniqueResultException
        |NoResultException
        |WrongPasswordException
        |InvalidArgumentException $e) {
            $this->responseBuilder->addMessage($e->getMessage());
        } catch (ORMException $e) {
            $this->responseBuilder->somethingWentWrong();
        }

        return $this->responseBuilder->jsonResponse();
    }

}
