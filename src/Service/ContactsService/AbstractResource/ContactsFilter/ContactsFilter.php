<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 10:04
*/

namespace App\Service\ContactsService\AbstractResource\ContactsFilter;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Modules\LazyLoading\LazyLoading;
use App\Repository\ContactProfileRepository;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use App\Service\ContactsService\AbstractResource\ContactsFilter\Sources\ByText;
use App\Service\ContactsService\AbstractResource\ContactsFilter\Sources\ByWorld;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

final class ContactsFilter extends LazyLoading
{

    public function __construct(
        private LoggerInterface $logger,
        private ContactProfileRepository $contactProfileRepository
    )
    {

    }

    #[Pure] public function byText(string $searchText): ByText
    {
        return (new ByText(
            $this->logger,
            $this->contactProfileRepository,
            $searchText
        ));
    }

    #[Pure] public function byWorld(string $worldName): ByWorld
    {
        return (new ByWorld(
            $this->logger,
            $this->contactProfileRepository,
            $worldName
        ));
    }


}
