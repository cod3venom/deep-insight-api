<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.01.2022
 * Time: 18:39
*/


namespace App\Controller\API\User;

use App\Modules\VirtualController\VirtualController;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
