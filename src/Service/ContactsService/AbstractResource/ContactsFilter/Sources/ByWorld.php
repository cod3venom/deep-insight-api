<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 19:23
*/

namespace App\Service\ContactsService\AbstractResource\ContactsFilter\Sources;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Repository\ContactProfileRepository;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ByWorld
{


    #[Pure] public function __construct(
        private LoggerInterface $logger,
        private ContactProfileRepository $contactProfileRepository,
        private string $worldName
    )
    {
    }

    private function transformInput(?string $input) {
        if (is_null($input)) {
            return '';
        }
        $input = strtolower($input);
        switch ($input) {
            case 'world of action':
                return 'worldOfAction';
            case 'world of matter':
                return 'worldOfMatter';
            case 'world of information':
                return 'worldOfInformation';
            case 'world of feeling':
                return 'worldOfFeeling';
            case 'world of fun':
                return 'worldOfFun';
            case 'world of usability':
                return 'worldOfUsability';
            case 'world of relations':
                return 'worldOfRelations';
            case 'world of desire&power':
                return 'worldOfDesireAndPower';
            case 'world of seek&explore':
                return 'worldOfSeekAndExplore';
            case 'world of career':
                return 'worldOfCareer';
            case 'world of future':
                return 'worldOfFuture';
            case 'world of spirituality':
                return 'worldOfSpirituality';
        }
    }

    /**
     * @param User $owner
     * @return array
     */
    public function handle(User $owner): array {

//        $ownerUserId = $owner->getUserId();
//        $orderingColumn = 'analysis.'.$this->transformInput($this->worldName);
//
//        $result = $this->contactProfileRepository->contactsSelectorQB()
//            ->andWhere('contact.ownerUserId = :ownerUserId')
//
//
//        $chunks = array_chunk($result, 3);
//
//        foreach ($chunks as $obj) {
//            $result[] = $this->contactProfileRepository->mapContactsToTraits($chunks);
//        }
        return [];
    }
}
