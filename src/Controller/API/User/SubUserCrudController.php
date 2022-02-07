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
use App\Entity\User\UserCompanyInfo;
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
            $userId = $this->user()->getUserId();
            $user = $this->userRepository->allSubUsers($userId);
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
        $password = password_hash($request->get('password'), PASSWORD_DEFAULT);


        $profile = $request->get('profile');
        $company = $request->get('company');

        try {

            $userAuthorId = $this->user()->getUserId();
            $userId = Uuid::uuid4()->toString();

            if ($this->userRepository->exists($email)) {
                return $this->responseBuilder->addMessage('User already exists')
                    ->setStatus(Response::HTTP_ALREADY_REPORTED)
                    ->jsonResponse();
            }


            $subUserAcc = new User();
            $subUserAcc->profile = new UserProfile();
            $subUserAcc->company = new UserCompanyInfo();

            $subUserAcc
                ->setUserId($userId)
                ->setUserAuthorId($userAuthorId)
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles([User::ROLE_SUB_USER])
                ->setLastLoginAt()
                ->setCreatedAt();

            $subUserAcc->profile->setUserId($userId)->setCreatedAt();
            $subUserAcc->company->setUserId($userId)->setCreatedAt();

            $subUserAcc->profile->arrayToEntity($profile);
            $subUserAcc->company->arrayToEntity($company);

            $this->userRepository->save($subUserAcc);

            return $this->responseBuilder
                ->addObject($subUserAcc->profile)
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
        $password = $request->get('password');

        $profile = $request->get('profile');
        $company = $request->get('company');



        try {


            if (!$this->userRepository->existsAsSubUser($subUserId)) {
                return $this->responseBuilder->addMessage('User does not  exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->jsonResponse();
            }

            $subUserAcc = $this->userRepository->findSubUserById($subUserId);

            if (is_null($subUserAcc->profile)) {
                $subUserAcc->profile = new UserProfile();
            }
            if (is_null($subUserAcc->company)) {
                $subUserAcc->company = new UserCompanyInfo();
            }

            if ($subUserAcc->getPassword() !== $password) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($email) {
                $subUserAcc
                    ->setEmail($email)
                    ->profile->setEmail($email);
            }
            if ($password) {
                $subUserAcc->setPassword($password);
            }

            $subUserAcc
                ->setUserId($subUserId)
                ->setUpdatedAt();
            $subUserAcc->profile
                ->setUserId($subUserId)
                ->setCreatedAt();;

            $subUserAcc->company
                ->setUserId($subUserId)
                ->setUpdatedAt();

            $subUserAcc->profile->arrayToEntity($profile);
            $subUserAcc->company->arrayToEntity($company);

            $this->userRepository->update($subUserAcc);

            $this->responseBuilder->addObject($subUserAcc);
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

            $user = $this->userRepository->findSubUserById($subUserId);
            $profile = $this->userProfileRepository->findSubUserById($subUserId);

            $this->userRepository->delete($user);
            $this->userProfileRepository->delete($profile);

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
     * @Route (path="/{userId}/set-avatar", methods={"POST"})
     * @param Request $request
     * @param string $userId
     * @return JsonResponse
     */
    public function setAvatar(Request $request, string $userId): JsonResponse
    {
        try {
            $avatarUrl = $request->get('avatar');

            $user = $this->userRepository->findUserById($userId);
            $user->profile->setAvatar($avatarUrl);

            $this->userProfileRepository->update($user->profile);
            $this->responseBuilder->addObject($user);
            return $this->responseBuilder->objectResponse();
        }
        catch (\Exception $ex) {
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

    /**
     * @Route (path="/search/{keyword}", methods={"POST"})
     * @param string $keyword
     * @return JsonResponse
     */
    public function searchForSubUser(string $keyword): JsonResponse
    {
        try {
            $profile = $this->userProfileRepository->searchForSubUser($keyword);
            return $this->responseBuilder->addObject($profile)
                ->setStatus(Response::HTTP_OK)
                ->objectResponse();
        }
        catch (\Exception $ex){
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
