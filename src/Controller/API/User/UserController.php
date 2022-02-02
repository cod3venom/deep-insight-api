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
use App\Helpers\DateHelper\DateHelper;
use App\Modules\VirtualController\VirtualController;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends VirtualController
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

    /**
     * @Route (path="/me", methods={"GET"})
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $userId = $this->user()->getUserId();
            $user = $this->userRepository->findUserById($userId);
            $this->responseBuilder->addObject($user);
            return $this->responseBuilder->objectResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/user/{userId}", methods={"GET"})
     * @param string $userId
     * @return JsonResponse
     */
    public function userById(string $userId): JsonResponse {
        try {
            $user = $this->userRepository->findUserById($userId);
            $this->responseBuilder->addObject($user);
            return $this->responseBuilder->objectResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $email = $request->get('email');
            $password = $request->get('password');

            $profile = $request->get('profile');
            $company = $request->get('company');

            $profile['birthDay'] = DateHelper::birthDay($profile['birthDay']);

            $userId = $this->user()->getUserId();

            $userAcc = $this->userRepository->findUserById($userId);
            if (!empty($email)) {
                $userAcc->setEmail($email);
            }
            if (!empty($password))  {
                $userAcc->setPassword(password_hash($password, PASSWORD_DEFAULT));
            }

            $userAcc->profile->arrayToEntity($profile);
            $userAcc->company->arrayToEntity($company);

            $this->userRepository->update($userAcc);

            $this->responseBuilder->addObject($userAcc);
            return $this->responseBuilder->objectResponse();

        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/user/{userId}/set-avatar", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function setAvatar(Request $request): JsonResponse
    {
        try {
            $avatarUrl = $request->get('avatar');

            $userId = $this->user()->getUserId();
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
}
