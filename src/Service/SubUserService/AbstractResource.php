<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.02.2022
 * Time: 16:42
*/

namespace App\Service\SubUserService;

use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use App\Service\SubUserService\AbstractResource\SubUsersExporter\SubUsersExporter;
use App\Service\SubUserService\AbstractResource\SubUsersFilter\SubUsersFilter;
use App\Service\SubUserService\AbstractResource\SubUsersImporter\SubUsersImporter;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

abstract class AbstractResource
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
        HumanTraitsService $humanTraitsService,
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

    /**
     * @return SubUsersImporter
     */
    #[Pure] public function SubUserImporter(): SubUsersImporter {
        return (new SubUsersImporter($this->logger));
    }

    /**
     * @return SubUsersExporter
     */
    #[Pure] public function SubUserExporter(): SubUsersExporter {
        return (new SubUsersExporter(
            $this->logger,
            $this->userRepository,
            $this->userProfileRepository,
            $this->traitAnalysisRepository,
            $this->traitItemRepository,
            $this->traitColorRepository,
            $this->humanTraitsService
        ));
    }

    /**
     * @return SubUsersFilter
     */
    #[Pure] public function SubUserFilter(): SubUsersFilter {
        return (new SubUsersFilter(
            $this->logger,
            $this->userRepository,
            $this->userProfileRepository,
            $this->traitAnalysisRepository,
            $this->traitItemRepository,
            $this->traitColorRepository,
            $this->humanTraitsService
        ));
    }
}
