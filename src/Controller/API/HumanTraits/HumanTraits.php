<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.01.2022
 * Time: 18:31
*/


namespace App\Controller\API\HumanTraits;

use App\Helpers\DateHelper\DateHelper;
use App\Modules\VirtualController\VirtualController;
use App\Repository\TraitAnalysisRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/me")
 */
class HumanTraits extends VirtualController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserProfileRepository
     */
    private UserProfileRepository $userProfileRepository;

    private TraitAnalysisRepository $traitAnalysisRepository;

    private HumanTraitsService $humanTraitsService;

    public function __construct(
        SerializerInterface $serializer,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        HumanTraitsService $humanTraitsService
    )
    {
        parent::__construct($serializer);
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->humanTraitsService = $humanTraitsService;
    }

    /**
     * @Route (path="/report")
     * @return JsonResponse
     */
    public function myReport(): JsonResponse
    {
        try{
            $userId = $this->user()->getUserId();
            $profile = $this->userProfileRepository->findSubUserById($userId);
            $birthday = $profile->getBirthDay()->format(DateHelper::BIRTH_DAY_FORMAT);
            $analysisReport = $this->traitAnalysisRepository->findTraitsByBirthDay($birthday);
            $defaultSchema =  $this->humanTraitsService->schemaBuilder()->buildFromObject($analysisReport);
            return $this->responseBuilder->addPayload($defaultSchema)->setStatus(Response::HTTP_OK)->jsonResponse();
        }
        catch (\Exception $ex){
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/sub-users/report/{userId}")
     * @param string $userId
     * @return JsonResponse
     */
    public function byUserId(string $userId): JsonResponse
    {
        try{
            $profile = $this->userProfileRepository->findSubUserById($userId);
            $birthDay = $profile->getBirthDay()->format(DateHelper::BIRTH_DAY_FORMAT);
            $analysisReport = $this->traitAnalysisRepository->findTraitsByBirthDay($birthDay);

            $defaultSchema = $this->humanTraitsService->schemaBuilder()->buildFromObject($analysisReport);
            return $this->responseBuilder->addPayload($defaultSchema)->setStatus(Response::HTTP_OK)->jsonResponse();
        }
        catch (\Exception $ex){
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}