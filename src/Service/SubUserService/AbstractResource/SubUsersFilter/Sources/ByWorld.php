<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 19:23
*/

namespace App\Service\SubUserService\AbstractResource\SubUsersFilter\Sources;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
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
    private string $authorUserId;
    private string $worldName;

    #[Pure] public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService,
        string $authorUserId,
        string $worldName
    )
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->traitItemRepository = $traitItemRepository;
        $this->traitColorRepository = $traitColorRepository;
        $this->humanTraitsService = $humanTraitsService;
        $this->authorUserId = $authorUserId;
        $this->worldName = $worldName;
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
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function handle(): array {

        $result = [];
        $world = $this->transformInput($this->worldName);
        $schemaBuilder = $this->humanTraitsService->schemaBuilder();

        $result = $this->userRepository->createQueryBuilder('user')
            ->select("user, profile, analysis")
            ->innerJoin(UserProfile::class, 'profile', 'WITH', 'user.userId = profile.userId')
            ->innerJoin(TraitAnalysis::class, 'analysis', 'WITH', "DATE_FORMAT(profile.birthDay, '%d-%m-%Y') = DATE_FORMAT(cast(analysis.birthDay as date), '%d-%m-%Y')")
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
        //$colorsReport = $schemaBuilder->buildWorldsFromObject($analyses, $this->traitItemRepository, $this->traitColorRepository);
        //$analysisReport =  $schemaBuilder->buildTraitsFromObject($analyses, $this->traitItemRepository);
        //$subUser->profile->setAnalysisReport($analysisReport);
        //$subUser->profile->setColorsReport($colorsReport);

        return $result;
    }
}
