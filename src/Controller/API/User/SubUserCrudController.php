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
use App\Modules\VirtualController\VirtualController;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/me")
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
        if (is_null($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
           throw new \InvalidArgumentException('Email is not valid');
        }
        if (is_null($firstName)) {
            throw new \InvalidArgumentException('First Name is not valid');
        }
        if (is_null($lastName)) {
            throw new \InvalidArgumentException('Last Name is not valid');
        }

        if (is_null($birthDay)) {
            throw new \InvalidArgumentException('Birth Day is not valid');
        }
    }
    /**
     * @Route (path="/sub-users/add", methods={"PUT"})
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

            $this->rulesValidation($email, $firstName, $lastName, $birthDay);

            $userAuthorId = $this->user()->getUserId(); # generate auth user id
            $userId = Uuid::uuid4()->toString(); # generate user id for the creating user
            $birthDay = new DateTime($birthDay); # port birth day string to the date time object

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

            $this->responseBuilder->addPayload([$subUserProfile]);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}