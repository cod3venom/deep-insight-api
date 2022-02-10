<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 08:34
*/

namespace App\Controller\API\HumanTraits;

use App\Entity\User\UserProfile;
use App\Modules\StrTransformer\StrTransformer;
use App\Modules\VirtualController\VirtualController;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route (path="/me")
 */
class HumanTraitsFilter extends VirtualController
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

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
     * @var TraitColorRepository
     */
    private TraitColorRepository $traitColorRepository;

    /**
     * @var HumanTraitsService
     */
    private HumanTraitsService $humanTraitsService;



    public function __construct(
        LoggerInterface $logger,
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
        $this->traitColorRepository = $traitColorRepository;
        $this->humanTraitsService = $humanTraitsService;
    }


    /**
     * @Route (path="/sub-users/filter-by/worlds/{world}", methods={"GET"})
     * @param Request $request
     * @param string $world
     * @return JsonResponse
     */
    public function filterByWorlds(Request $request, string $world): JsonResponse
    {
        try{

            $startFrom = (int)$request->get('startFrom');
            $limit = (int)$request->get('limit');

            $world = StrTransformer::strToProperty($world);

            $userId = $this->user()->getUserId();
            $subUsers = $this->userRepository
                ->setStartFrom($startFrom)
                ->setLimit($limit)
                ->allSubUsers($userId, true);

            $this->responseBuilder->addPayload($subUsers);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex){
            $this->logger->error('SOME ERROR', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
