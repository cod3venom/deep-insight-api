<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.01.2022
 * Time: 18:31
*/


namespace App\Controller\API\HumanTraits;

use App\Entity\User\UserProfile;
use App\Helpers\DateHelper\DateHelper;
use App\Modules\VirtualController\VirtualController;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use App\Service\LoggerService\LoggerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/me")
 */
class HumanTraitsController extends VirtualController
{
    /**
     * @var LoggerService
     */
    private LoggerService $logger;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserProfileRepository
     */
    private UserProfileRepository $userProfileRepository;

    /**
     * @var TraitAnalysisRepository
     */
    private TraitAnalysisRepository $traitAnalysisRepository;

    /**
     * @var TraitItemRepository
     */
    private TraitItemRepository $traitItemRepository;


    /**
     * @var HumanTraitsService
     */
    private HumanTraitsService $humanTraitsService;



    public function __construct(
        LoggerService $logger,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService
    )
    {
        parent::__construct($serializer);
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->traitItemRepository = $traitItemRepository;
        $this->humanTraitsService = $humanTraitsService;
    }

    /**
     * @Route (path="/report")
     * @return JsonResponse
     */
    public function reportForMe(): JsonResponse
    {
        try{
            $userId = $this->user()->getUserId();
            $profile = $this->userProfileRepository->findSubUserById($userId);
            $birthDay = date_format($profile->getBirthDay(), UserProfile::BirthDayFormat);

            $analysisReport = $this->traitAnalysisRepository->findTraitsByBirthDay($birthDay);
            $defaultSchema =  $this->humanTraitsService->schemaBuilder()->buildTraitsFromObject($analysisReport, $this->traitItemRepository);
            return $this->responseBuilder->addPayload($defaultSchema)->setStatus(Response::HTTP_OK)->jsonResponse();
        }
        catch (\Exception $ex){
            $this->logger->error('HumanTraitsController' ,'reportForMe', [$this->user(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/sub-users/report/{userId}")
     * @param string $userId
     * @return JsonResponse
     */
    public function reportForSubUser(string $userId): JsonResponse
    {
        try{
            $profile = $this->userProfileRepository->findSubUserById($userId);
            $birthDay = date_format($profile->getBirthDay(), UserProfile::BirthDayFormat);
            $analysisReport = $this->traitAnalysisRepository->findTraitsByBirthDay($birthDay);

            $defaultSchema = $this->humanTraitsService->schemaBuilder()->buildTraitsFromObject($analysisReport, $this->traitItemRepository);
            return $this->responseBuilder->addPayload($defaultSchema)->setStatus(Response::HTTP_OK)->jsonResponse();
        }
        catch (\Exception $ex){
            $this->logger->error('HumanTraitsController', 'reportForSubUser', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
