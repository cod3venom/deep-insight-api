<?php

namespace App\Repository;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactProfile[]    findAll()
 * @method ContactProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactProfileRepository extends ServiceEntityRepository
{
    public int $startFrom = 0;
    public int $limit = 0;


    public function __construct(
        ManagerRegistry $registry,
        private UserRepository $userRepository
    )
    {
        parent::__construct($registry, ContactProfile::class);
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

    public function mapContactToOwner(User $owner): QueryBuilder {
       return $this->createQueryBuilder('contact')
           ->select(['contact, company, analysis'])
           ->leftJoin(ContactCompany::class, 'company', 'WITH', 'company.id = contact.id')
           ->leftJoin(TraitAnalysis::class, 'analysis', 'WITH', "DATE_FORMAT(contact.birthDay, '%d/%m/%Y') = DATE_FORMAT(cast(analysis.birthDay as date), '%d/%m/%Y')")
           ->where('contact.ownerUserId = :ownerUserId');
    }

    public function mapScalarContactToOwnerObjects(array $scalar): array
    {
        $chunkedList = array_chunk($scalar, 3);
        $tmpUser = new User();
        $tmpContact = new ContactProfile();
        $tmpCompany = new ContactCompany();
        $tmpTrait = new TraitAnalysis();
        $result = [];

        foreach ($chunkedList as $chunk) {
            foreach ($chunk as $obj) {
                if ($obj instanceof ContactProfile) {
                    $tmpContact = $obj;
                }
                else if ($obj instanceof ContactCompany) {
                    $tmpCompany = $obj;
                }
                else if ($obj instanceof TraitAnalysis) {
                    $tmpTrait = $obj;
                }
            }

            $tmpContact->setContactCompany($tmpCompany);
            $tmpContact->setTraitAnalysis($tmpTrait);
            $result[] = $tmpContact;
        }
        return $result;
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

    public function all(User $owner): array
    {
        $map = $this->mapContactToOwner($owner);

        if ($this->limit > 0) {
            $map->setFirstResult($this->startFrom)->setMaxResults($this->limit);
        }

        $map = $map->setParameter('ownerUserId', $owner->getUserId())
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);

        return $this->mapScalarContactToOwnerObjects($map);
    }

    public function findByContactId(string $contactId):ContactProfile {
        try {
            $contact = $this->createQueryBuilder('contact')
                ->andWhere('contact.contactId = :contactId')
                ->setMaxResults(1)
                ->setParameter('contactId', $contactId)
                ->getQuery()
                ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
        }
        catch (\Exception $ex) {
            $contact = new ContactProfile();
        }
        return $contact;
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
            ->setOwner($owner)
            ->setOwnerUserId($owner->getUserId())
            ->setCreatedAt();

        $company
            ->setCreatedAt();

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
        $contact
            ->setUpdatedAt();
        $this->save($contact);

        return $contact;
    }


    /**
     * @param ContactProfile $contact
     * @return ContactProfile
     * @throws ORMException
     * @throws OptimisticLockException
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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(ContactProfile $contactProfile)
    {
        $this->_em->remove($contactProfile);
        $this->_em->flush();
    }
}
