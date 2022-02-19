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
use App\Modules\StringBuilder\StringBuilder;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ByText
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
    private string $searchText;


    private array $blackList = [];

    private array $subUsers = [];


    #[Pure] public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService,
        string $authorUserId,
        string $searchText
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
        $this->searchText = $searchText;
    }


    /**
     * @param string $searchText
     * @return array
     */
    private function searchTextToKeyWords(string $searchText): array
    {
        $keywords = (array)explode(',', $searchText);
        if (count($keywords) === 0){
            return [$this->searchText];
        }
        return $keywords;
    }

    private function clean($string): string {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    /**
     * @param array $keywords
     * @return string
     */
    private function buildQuery(array $keywords): string
    {
        $andOpExists = false;
        $sql = "
                SELECT     profile.user_id
                FROM       \"user\" AS usr
                inner join user_profile profile
                ON         usr.user_id = profile.user_id
                inner join user_company_info company
                ON         company.user_id = usr.user_id
                WHERE     
                
                 (usr.roles like '%ROLE_SUB_USER%' AND usr.user_author_id = :userAuthorId)
                    AND (
               
            ";


        for ($i = 0; $i < count($keywords); $i++) {
            $placeHolder = 'keyword'.$i;
            $placeHolders[] = $placeHolder;

            if (!$andOpExists) {
                $sql .="
                    (   
                        LOWER(concat(profile.first_name, ' ', profile.last_name)) LIKE LOWER('%$placeHolder%')
                        
                        OR
                        
                         to_tsvector(profile::text) @@ plainto_tsquery('$placeHolder')
                         
                        OR
                             to_tsvector(company::text) @@ plainto_tsquery('$placeHolder')
                    )
                ";
                $andOpExists = true;
            }else {
                $sql .= "
                  OR
                    (     
                        LOWER(concat(profile.first_name, ' ', profile.last_name)) LIKE LOWER('%$placeHolder%')
                        
                        OR
                        
                        to_tsvector(profile::text) @@ plainto_tsquery('$placeHolder')
                        
                        OR
                        
                         to_tsvector(company::text) @@ plainto_tsquery('$placeHolder')
                    )
                ";
            }

        }

        $sql .= ' )';

        for ($i = 0; $i < count($keywords); $i++) {
            $placeHolder = 'keyword'.$i;
            $keyword = strtolower($keywords[$i]);

            //$keyword = $this->clean($keyword);
            $sql = str_replace($placeHolder, $keyword, $sql);
        }
        return $sql;
    }

    /**
     * @return array
     * @throws NonUniqueResultException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    public function handle(): array {

        $keywords = $this->searchTextToKeyWords($this->searchText);

        if (count($keywords) === 0) {
            return [];
        }

        $sql = $this->buildQuery($keywords);
        $conn = $this->userRepository->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('userAuthorId', $this->authorUserId);
        $stmt->execute();
        $subUserIds = $stmt->fetchAll();

        for ($i = 0; $i < count($subUserIds); $i ++) {

            $subUserId = $subUserIds[$i];
            if (!isset($subUserId['user_id'])) {
                continue;
            }
            $subUserId = $subUserId['user_id'];

            // getSubUserPackById was replaced by findUserPackById
            // in order to also see main user search results
            // in the /me/contacts searching screen.
            // 18/02/2022
            $subUser = $this->userRepository->findUserPackById($subUserId);

            if (!(array)$subUser) {
                continue;
            }

            if (empty($subUser->getUserId())) {
                continue;
            }

            if (in_array($subUser->getEmail(), $this->blackList)) {
                continue;
            }

            $this->blackList[] = $subUser->getEmail();
            $this->subUsers[] = $subUser;
        }

        return $this->subUsers;
    }
}
