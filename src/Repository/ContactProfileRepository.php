<?php

namespace App\Repository;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Service\ContactsService\ContactsService;
use App\Service\HumanTraitServices\Helpers\SchemaBuilder\SchemaBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ContactProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactProfile[]    findAll()
 * @method ContactProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactProfileRepository extends ServiceEntityRepository
{
	
	private SchemaBuilder $schemaBuilder;
	
	/**
	 * @var int
	 */
    public int $startFrom = 0;
	
	/**
	 * @var int
	 */
    public int $limit = 0;
	

    public function __construct(
        ManagerRegistry $registry,
        private ContactsService $contactsService,
        private ImportedContactRepository $importedContactRepository,
		private TraitAnalysisRepository $traitAnalysisRepository,
        private TraitItemRepository $traitItemRepository,
		private TraitColorRepository $traitColorRepository
    )
    {
        parent::__construct($registry, ContactProfile::class);
		$this->schemaBuilder = new SchemaBuilder();
    }

    public function setStartFrom(int $startFrom): self {
        $this->startFrom = $startFrom;

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit  = $limit;

        return $this;
    }
	
	/**
	 * @return QueryBuilder
	 */
    public function contactsSelectorQB(): QueryBuilder
	{
        return $this->createQueryBuilder('contact')
            ->select('contact')
			->innerJoin(ContactCompany::class, 'company', 'WITH', 'company.id = contact.id')
			->where('contact.ownerUserId = :ownerUserId');
    }
	
	
	/**
	 * @param array $contacts
	 * @return array
	 */
    public function mapContactsToTraits(array $contacts): array
	{
		$result = [];
		
		foreach ($contacts as $contact) {
			if (!($contact instanceof ContactProfile)) continue;
			
			$result[] = $this->mapSingleContactToTrait($contact);
		}
		return  $result;
    }
	
	/**
	 * @param ContactProfile $contact
	 * @return ContactProfile
	 */
	public function mapSingleContactToTrait(ContactProfile $contact): ContactProfile
	{
		$traits = $this->traitAnalysisRepository->findTraitsByBirthDay($contact->getBirthDay());
		$contact->setTraitAnalysis($traits);
		$contact->setAnalysisReport($this->schemaBuilder->buildTraitsFromObject($traits, $this->traitItemRepository));
		$contact->setColorsReport($this->schemaBuilder->buildWorldsFromObject($traits,  $this->traitItemRepository, $this->traitColorRepository));
		return $contact;
	}
	
	
	
	/**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function isContactExists(User $owner, ContactProfile $contactProfile): bool
    {
        $total = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.firstName = :firstName')
            ->andWhere('c.lastName = :lastName')
            ->andWhere('c.birthDay = :birthDay')
            ->andWhere('c.ownerUserId = :ownerUserId')
            ->setParameter('firstName', $contactProfile->getFirstName())
            ->setParameter('lastName', $contactProfile->getLastName())
            ->setParameter('ownerUserId', $owner->getUserId())
            ->getQuery()
            ->getSingleScalarResult();

        return ($total > 0);
    }

    /**
     * @param User $owner
     * @return array
     */
    public function all(User $owner): array
    {
		$qb = $this->contactsSelectorQB();

        if ($this->limit > 0) {
			$qb->setFirstResult($this->startFrom)->setMaxResults($this->limit);
        }

        $contacts = $qb->setParameter('ownerUserId', $owner->getUserId())->getQuery()->getResult();
		return $this->mapContactsToTraits($contacts);
    }

    /**
     * @param User $owner
     * @param string $contactId
     * @return ContactProfile
     */
    public function findContactPackById(User $owner, string $contactId):ContactProfile {
        try {
            $ownerId = $owner->getUserId();

            $contact = $this->contactsSelectorQB()
                ->andWhere('contact.contactId = :contactId')
                ->andWhere('contact.ownerUserId = :ownerUserId')
                ->setMaxResults(1)
                ->setParameter('contactId', $contactId)
                ->setParameter('ownerUserId', $ownerId)
                ->getQuery()
                ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);

            $contact = $this->mapSingleContactToTrait($contact);
        }
        catch (\Exception $ex) {
            $contact = new ContactProfile();
        }
        return $contact;
    }

    /**
     * @param User $owner
     * @param string $contactId
     * @return ContactProfile
     */
    public function findContactById(User $owner, string $contactId): ContactProfile{
        try {
            $ownerUserId = $owner->getUserId();
            return $this->createQueryBuilder('contact')
                ->andWhere('contact.contactId = :contactId')
                ->andWhere('contact.ownerUserId = :ownerUserId')
                ->setMaxResults(1)
                ->setParameter('contactId', $contactId)
                ->setParameter('ownerUserId', $ownerUserId)
                ->getQuery()
                ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
        }
        catch (\Exception $ex){
            return new ContactProfile();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function search(User $owner, string $searchText): array
    {
        return $this->contactsService->filter($this)->byText($searchText)->handle($owner);
    }

    /**
     * @param User $owner
     * @param string $worldName
     * @return array
     */
    public function filterByWorld(User $owner, string $worldName): array
    {
        return $this->contactsService->filter($this)->byWorld($worldName)->handle($owner);
    }

    /**
     * @throws \Exception
     */
    public function importFromFile(string $targetDir, User $owner, UploadedFile $file): void
    {
         $this->contactsService->importer()->import($targetDir, $owner, $file, $this, $this->importedContactRepository);
    }

    /**
     * @param User $owner
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportToFile(User $owner): array
    {
        return $this->contactsService->exporter()->export($owner, $this);
    }

    /**
     * @param User $owner
     * @param ContactProfile $contact
     * @param ContactCompany $company
     * @return ContactProfile
     */
    public function create(User $owner, ContactProfile $contact, ContactCompany $company): ContactProfile
    {
        $contact
            ->genContactId()
            ->setOwnerUserId($owner->getUserId())
            ->setCreatedAt();

        $company->setCreatedAt();
        $contact->setContactCompany($company);
        $this->save($contact);

        return $contact;
    }

    /**
     * @param ContactProfile $contact
     * @return ContactProfile
     */
    public function edit(ContactProfile $contact): ContactProfile
    {
        $contact->setUpdatedAt();
        $contact->getContactCompany()->setUpdatedAt();
        $this->save($contact);

        return $contact;
    }
	
	
	/**
	 * @param ContactProfile $contact
	 * @return ContactProfile
	 */
    public function remove(ContactProfile $contact): ContactProfile
    {
        $this->delete($contact);

        return $contact;
    }


    public function getEntityManager(): EntityManagerInterface
    {
        return $this->_em;
    }

    /**
     * @param ContactProfile $contactProfile
     * @return void
     */
    public function save(ContactProfile $contactProfile)
    {
        $this->_em->persist($contactProfile);
        $this->_em->flush();
    }

    /**
     * @param ContactProfile $contactProfile
     * @return void
     */
    public function update(ContactProfile $contactProfile)
    {
        $this->_em->flush();
    }

    /**
     * @param ContactProfile $contactProfile
     * @return void
     */
    public function delete(ContactProfile $contactProfile)
    {
        $this->_em->remove($contactProfile);
        $this->_em->flush();
    }
}
