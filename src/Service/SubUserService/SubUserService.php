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
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

final class SubUserService extends AbstractResource
{
    #[Pure] public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService
    )
    {
        parent::__construct(
            $logger,
            $userRepository,
            $userProfileRepository,
            $traitAnalysisRepository,
            $traitItemRepository,
            $traitColorRepository,
            $humanTraitsService
        );
    }
}
