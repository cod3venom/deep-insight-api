<?php

namespace App\Entity\Contact;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\User\User;
use App\Helpers\DateHelper\DateHelper;
use App\Modules\Reflector\Reflector;
use App\Repository\ContactProfileRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ContactProfileRepository::class)
 */
class ContactProfile
{

    use IdTrait;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="uuid", length=255, nullable=true)
     */
    public string $contactId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="uuid", length=255, nullable=true)
     */
    public string $ownerUserId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstName = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastName = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $photo = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTime $birthDay = null;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $placeOfBirth = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $positionInTheCompany = '';

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private ?string $linksToProfiles = '';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $notes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country;



    private ?TraitAnalysis $traitAnalysis = null;

    private ?array $analysisReport = [];

    private ?array $colorsReport = [];

    /**
     * @ORM\OneToOne(targetEntity=ContactCompany::class, mappedBy="contact", cascade={"persist", "remove", "detach", "refresh"})
     */
    private ?ContactCompany $contactCompany;



    use UpdatedTrait;
    use CreatedTrait;

    public function __construct()
    {
		$this->contactCompany = new ContactCompany();
		
        if (isset($this->birthDay)) {
            $this->birthDay->format(DateHelper::HTML_INPUT_FORMAT);
        }
     }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function genContactId(): self {
        $this->contactId =  Uuid::uuid4()->toString();

        return $this;
    }

    public function getContactId(): ?string
    {
        return $this->contactId;
    }

    public function setContactId(string $contactId): self {
        $this->contactId = $contactId;

        return $this;
    }

    public function getOwnerUserId(): ?string
    {
        return $this->ownerUserId;
    }

    public function setOwnerUserId(string $ownerUserId): self {
        $this->ownerUserId = $ownerUserId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

	public function getStrBirthDay(): string
	{
		
		if ($this->birthDay instanceof \DateTimeInterface) {
			return $this->birthDay->format(DateHelper::HTML_INPUT_FORMAT);
		}
		return '';
	}
	
    public function getBirthDay():\DateTimeInterface
    {
		if ($this->birthDay instanceof \DateTimeInterface) {
			$this->birthDay->format(DateHelper::HTML_INPUT_FORMAT);
			return $this->birthDay;
		}
		$new = new \DateTime();
		$new->format(DateHelper::HTML_INPUT_FORMAT);;
        return new $new;
    }

    public function setBirthDay(?\DateTimeInterface $birthDay): self
    {
        $birthDay->format(DateHelper::BIRTH_DAY_FORMAT);
        $this->birthDay = $birthDay;

        return $this;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    public function setPlaceOfBirth(?string $placeOfBirth): self
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    public function getPositionInTheCompany(): ?string
    {
        return $this->positionInTheCompany;
    }

    public function setPositionInTheCompany(?string $positionInTheCompany): self
    {
        $this->positionInTheCompany = $positionInTheCompany;

        return $this;
    }

    public function getLinksToProfiles(): ?string
    {
        return $this->linksToProfiles;
    }

    public function setLinksToProfiles(?string $linksToProfiles): self
    {
        $this->linksToProfiles = $linksToProfiles;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }


    public function getContactCompany(): ?ContactCompany
    {
        return $this->contactCompany;
    }

    public function setContactCompany(ContactCompany|array|null $contactCompany): self
    {
        // unset the owning side of the relation if necessary
        if ($contactCompany === null && $this->contactCompany !== null) {
            $this->contactCompany->setContact(null);
        }

        // set the owning side of the relation if necessary
        if ($contactCompany !== null && $contactCompany->getContact() !== $this) {
            $contactCompany->setContact($this);
        }

        $this->contactCompany = $contactCompany;

        return $this;
    }


    public function getTraitAnalysis():? TraitAnalysis {
        return $this->traitAnalysis;
    }

    public function setTraitAnalysis(?TraitAnalysis $traitAnalysis): self
    {
        $this->traitAnalysis = $traitAnalysis;

        return $this;
    }

    public function getAnalysisReport(): ?array {
        return $this->analysisReport;
    }

    public function setAnalysisReport(?array $report): self
    {
        $this->analysisReport = $report;

        return $this;
    }

    public function getColorsReport(): ?array {
        return $this->colorsReport;
    }

    public function setColorsReport(?array $colorsReport): self
    {
        $this->colorsReport = $colorsReport;

        return $this;
    }

    /**
     * @throws \ReflectionException
     */
    public function arrayToEntity($input): self {
        Reflector::arrayToEntity($this, $input);
        return $this;
    }
}
