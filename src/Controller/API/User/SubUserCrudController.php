<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.01.2022
 * Time: 18:39
*/


namespace App\Controller\API\User;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Helpers\DateHelper\DateHelper;
use App\Modules\VirtualController\VirtualController;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/me/sub-users")
 */
class SubUserCrudController extends VirtualController
{

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserProfileRepository
     */
    private UserProfileRepository $userProfileRepository;

    public function __construct(SerializerInterface $serializer, UserRepository $userRepository,UserProfileRepository $userProfileRepository)
    {
        parent::__construct($serializer);
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    private function rulesValidation($email, $firstName, $lastName, $birthDay)
    {
        if (empty($email) ||  !filter_var($email, FILTER_VALIDATE_EMAIL)) {
           throw new \InvalidArgumentException('Email is not valid');
        }
        if (empty($firstName)) {
            throw new \InvalidArgumentException('First Name is not valid');
        }
        if (empty($lastName)) {
            throw new \InvalidArgumentException('Last Name is not valid');
        }

        if (empty($birthDay)) {
            throw new \InvalidArgumentException('Birth Day is not valid');
        }
    }

    /**
     * @Route (path="/", methods={"GET"})
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $user = $this->userRepository->allSubUsers();
            $this->responseBuilder->addPayload($user);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $password = password_hash(microtime(), PASSWORD_DEFAULT);

        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $phone = $request->get('phone');

        $birthDay = $request->get('birthDay');
        $avatar = $request->get('avatar');

        try {

            # Validate input or throw @InvalidArgumentException
            $this->rulesValidation($email, $firstName, $lastName, $birthDay);

            $userAuthorId = $this->user()->getUserId(); # generate auth user id
            $userId = Uuid::uuid4()->toString(); # generate user id for the creating user
            $birthDay = DateHelper::slashToDash($birthDay); # port birth day string to the date time object


            # Check if user already exists
            if ($this->userRepository->exists($email)) {
                return $this->responseBuilder->addMessage('User already exists')
                    ->setStatus(Response::HTTP_ALREADY_REPORTED)
                    ->jsonResponse();
            }


            $subUserAcc = new User();
            $subUserProfile = new UserProfile();

            $subUserAcc
                ->setUserId($userId)
                ->setUserAuthorId($userAuthorId)
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles([User::ROLE_SUB_USER])
                ->setLastLoginAt()
                ->setCreatedAt();

            $subUserProfile
                ->setUserId($userId)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPhone($phone)
                ->setBirthDay($birthDay)
                ->setAvatar($avatar)
                ->setCreatedAt();

            $this->userRepository->save($subUserAcc);
            $this->userProfileRepository->save($subUserProfile);

            return $this->responseBuilder
                ->addObject($subUserProfile)
                ->objectResponse();

        }
        catch (\InvalidArgumentException $ex){
            return $this->responseBuilder
                ->addMessage($ex->getMessage())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/update/{subUserId}", methods={"PUT"})
     * @param Request $request
     * @param $subUserId
     * @return JsonResponse
     */
    public function update(Request $request, $subUserId): JsonResponse
    {
        $email = $request->get('email');
        $password = password_hash(microtime(), PASSWORD_DEFAULT);

        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $phone = $request->get('phone');

        $birthDay = $request->get('birthDay');
        $avatar = $request->get('avatar');

        try {


            # Validate input or throw @InvalidArgumentException
            $this->rulesValidation($email, $firstName, $lastName, $birthDay);

            $userAuthorId = $this->user()->getUserId(); # generate auth user id
            $birthDay = DateHelper::slashToDash($birthDay); # port birth day string to the date time object

            # Check if user already exists
            if (!$this->userRepository->existByUserId($subUserId)) {
                return $this->responseBuilder->addMessage('User does not  exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->jsonResponse();
            }

            $subUserAcc = $this->userRepository->findUserById($subUserId);
            $subUserProfile = $this->userProfileRepository->findSubUserById($subUserId);

            $subUserAcc
                ->setUserAuthorId($userAuthorId)
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles([User::ROLE_SUB_USER])
                ->setLastLoginAt()
                ->setCreatedAt();

            $subUserProfile
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPhone($phone)
                ->setBirthDay($birthDay)
                ->setAvatar($avatar)
                ->setCreatedAt();

            $this->userRepository->update($subUserAcc);
            $this->userProfileRepository->update($subUserProfile);

            $this->responseBuilder->addObject($subUserProfile);
            return $this->responseBuilder->objectResponse();
        }
        catch (\InvalidArgumentException $ex){
            return $this->responseBuilder
                ->addMessage($ex->getMessage())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/delete/{subUserId}", methods={"DELETE"})
     * @param string $subUserId
     * @return JsonResponse
     */
    public function delete(string $subUserId): JsonResponse
    {
        try {
            $accExists = $this->userRepository->existByUserId($subUserId);
            if (!$accExists) {
                return $this->responseBuilder->addMessage('User does not  exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->jsonResponse();
            }

            $user = $this->userRepository->findUserById($subUserId);

            $this->userRepository->delete($user);
            $this->userProfileRepository->delete($user->getProfile());

            return $this->responseBuilder->addPayload([])
                ->setStatus(Response::HTTP_OK)
                ->jsonResponse();
        }
        catch (NoResultException){
            return $this->responseBuilder->addMessage('User does not exists')
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->jsonResponse();
        }
        catch (\Exception $ex){
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/{subUserId}", methods={"GET"})
     * @param string $subUserId
     * @return JsonResponse
     */
    public function getSubUser(string $subUserId): JsonResponse
    {
        try {
            $profile = $this->userRepository->findSubUserById($subUserId);
            return $this->responseBuilder->addObject($profile)
                ->setStatus(Response::HTTP_OK)
                ->objectResponse();
        }
        catch (\Exception $ex){
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}