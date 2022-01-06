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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/me")
 */
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
     * @Route (path="/", methods={"GET"})
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $userId = $this->user()->getUserId();
            $user = $this->userProfileRepository->getProfile($userId);
            $this->responseBuilder->addPayload($user);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/sub-users", methods={"GET"})
     * @return JsonResponse
     */
    public function subUsers(): JsonResponse
    {
        try {
            $user = $this->userProfileRepository->allSubUsers();
            $this->responseBuilder->addPayload($user);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/sub-users/{subUserId}", methods={"GET"})
     * @param string $subUserId
     * @return JsonResponse
     */
    public function subUserById(string $subUserId): JsonResponse
    {
        try {
            $user = $this->userProfileRepository->findSubUserById($subUserId);
            $this->responseBuilder->addPayload($user);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}