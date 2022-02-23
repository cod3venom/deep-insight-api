<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 19:23
*/

namespace App\Service\ContactsService\AbstractResource\ContactsFilter\Sources;

use App\Entity\Contact\ContactProfile;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Modules\StringBuilder\StringBuilder;
use App\Repository\ContactProfileRepository;
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
	
	
    #[Pure] public function __construct(
        private LoggerInterface $logger,
        private ContactProfileRepository $contactProfileRepository,
        private string $searchText
    ){}


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
	
    /**
     * @param array $keywords
     * @return string
     */
    private function buildQuery(array $keywords): string
    {
        $andOpExists = false;
        $sql = "
                SELECT     contact.contact_id
                FROM       contact_profile contact
                inner join contact_company company
                ON         contact.id = company.id
                WHERE
                
                           contact.owner_user_id = :ownerUserId
                AND (
               
            ";


        for ($i = 0; $i < count($keywords); $i++) {
            $placeHolder = 'keyword'.$i;
            $placeHolders[] = $placeHolder;

            if (!$andOpExists) {
                $sql .="
                    (
                        LOWER(concat(contact.first_name, ' ', contact.last_name)) LIKE LOWER('%$placeHolder%')
                        
                        OR
                        
                         to_tsvector(contact::text) @@ plainto_tsquery('$placeHolder')
                         
                        OR
                             to_tsvector(company::text) @@ plainto_tsquery('$placeHolder')
                    )
                ";
                $andOpExists = true;
            }else {
                $sql .= "
                  OR
                    (
                        LOWER(concat(contact.first_name, ' ', contact.last_name)) LIKE LOWER('%$placeHolder%')
                        
                        OR
                        
                        to_tsvector(contact::text) @@ plainto_tsquery('$placeHolder')
                        
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
			$sql = str_replace($placeHolder, $keyword, $sql);
        }
        return $sql;
    }

    /**
     * @param User $owner
     * @return array
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function handle(User $owner): array {

        $keywords = $this->searchTextToKeyWords($this->searchText);
        $ownerUserId = $owner->getUserId();
        if (count($keywords) === 0) {
            return [];
        }

        $sql = $this->buildQuery($keywords);
        $conn = $this->contactProfileRepository->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('ownerUserId', $ownerUserId);
        $stmt->execute();
        $contactsId = $stmt->fetchAll();

		$contacts = [];
        for ($i = 0; $i < count($contactsId); $i ++) {

            $contactId = $contactsId[$i];
            if (!isset($contactId['contact_id'])) {
                continue;
            }
            $contactId = $contactId['contact_id'];
	
			try {
				$contact = $this->contactProfileRepository->contactsSelectorQB()
					->andWhere('contact.contactId = :contactId')
					->andWhere('contact.ownerUserId = :ownerUserId')
					->setParameter('contactId', $contactId)
					->setParameter('ownerUserId', $ownerUserId)
					->getQuery()
					->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
				
				if (!($contact instanceof ContactProfile)) continue;
				
				$contacts[] = $this->contactProfileRepository->mapSingleContactToTrait($contact);
			} catch (NoResultException | NonUniqueResultException $e) {
			
			}
        }

        return $contacts;
    }
}
