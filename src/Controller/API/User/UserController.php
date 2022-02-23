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
use App\Service\LoggerService\LoggerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends VirtualController
{

    public function __construct(
        SerializerInterface $serializer,
        private LoggerService $logger,
        private UserRepository $userRepository,
        private UserProfileRepository $userProfileRepository
    )
    {
        parent::__construct($serializer);
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
            return $this->responseBuilder->addObject($user)->objectResponse();
        }
        catch (\Exception $ex) {
            $this->logger->error('UserController', 'me', [$this->user(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/me/set-avatar", methods={"POST"})
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
            return $this->responseBuilder->addObject($user)->objectResponse();
        }
        catch (\Exception $ex) {
            $this->logger->error('UserController', 'setAvatar', [$this->user(), $ex]);
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

            $userAcc = $this->userRepository->findUserById($this->user()->getUserId());

            if (!empty($email)) {
                $userAcc->setEmail($email);
            }
            if (!empty($password) && $userAcc->getPassword() !== $password) {
                $userAcc->setPassword(password_hash($password, PASSWORD_DEFAULT));
            }

            if (is_null($userAcc->profile)) {
                $userAcc->profile = new UserProfile();
                $userAcc->profile->setUserId($this->user()->getUserId())->setCreatedAt();
            }

            $userAcc->profile->arrayToEntity($profile);
            $this->userRepository->update($userAcc);
            return $this->responseBuilder->addObject($userAcc)->objectResponse();

        }
        catch (\Exception $ex) {
            $this->logger->error('UserController', 'update', [$this->user(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
