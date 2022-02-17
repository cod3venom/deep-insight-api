<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 10:04
*/

namespace App\Service\SubUserService\AbstractResource\SubUsersFilter;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Modules\LazyLoading\LazyLoading;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use App\Service\SubUserService\AbstractResource\SubUsersFilter\Sources\ByText;
use App\Service\SubUserService\AbstractResource\SubUsersFilter\Sources\ByWorld;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

final class SubUsersFilter extends LazyLoading
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


    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     * @param UserProfileRepository $userProfileRepository
     * @param TraitAnalysisRepository $traitAnalysisRepository
     * @param TraitItemRepository $traitItemRepository
     * @param TraitColorRepository $traitColorRepository
     * @param HumanTraitsService $humanTraitsService
     */
    public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService
    )
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->traitItemRepository = $traitItemRepository;
        $this->traitColorRepository = $traitColorRepository;
        $this->humanTraitsService = $humanTraitsService;
    }

    #[Pure] public function byText(string $authorUserId, string $searchText): ByText
    {
        return (new ByText(
            $this->logger,
            $this->userRepository,
            $this->userProfileRepository,
            $this->traitAnalysisRepository,
            $this->traitItemRepository,
            $this->traitColorRepository,
            $this->humanTraitsService,
            $authorUserId,
            $searchText
        ));
    }

    #[Pure] public function byWorld(string $authorUserId, string $worldName): ByWorld
    {
        return (new ByWorld(
            $this->logger,
            $this->userRepository,
            $this->userProfileRepository,
            $this->traitAnalysisRepository,
            $this->traitItemRepository,
            $this->traitColorRepository,
            $this->humanTraitsService,
            $authorUserId,
            $worldName
        ));
    }


}
