<?php

namespace App\Entity\Contact;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\User\User;
use App\Repository\ContactProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactProfileRepository::class)
 */
class ContactProfile
{

    public const BirthDayFormat = 'd/m/Y';

    use IdTrait;


    /**
     * @ORM\Column(type="uuid")
     */
    private string $ownerUserId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstName = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastName = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $photo = '';

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTime $birthDay = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $placeOfBirth = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $positionInTheCompany = '';

    /**
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

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contactProfiles")
     */
    private ?User $owner;


    public ?TraitAnalysis $traitAnalysis = null;

    private ?array $analysisReport = [];

    private ?array $colorsReport = [];

    /**
     * @ORM\OneToOne(targetEntity=ContactCompany::class, mappedBy="contact", cascade={"persist", "remove"})
     */
    private ?ContactCompany $contactCompany = null;

    use UpdatedTrait;
    use CreatedTrait;

    public function __construct()
    {
        if (isset($this->birthDay)) {
            $this->birthDay->format(self::BirthDayFormat);
        }
     }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnerUserId(): string
    {
        return $this->ownerUserId;
    }

    public function setOwnerUserId($ownerUserId): self
    {
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

    public function getBirthDay(): ?\DateTimeInterface
    {
        return $this->birthDay;
    }

    public function setBirthDay(?\DateTimeInterface $birthDay): self
    {
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getContactCompany(): ?ContactCompany
    {
        return $this->contactCompany;
    }

    public function setContactCompany(?ContactCompany $contactCompany): self
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

}
