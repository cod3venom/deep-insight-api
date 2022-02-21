<?php

namespace App\Entity\User;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UuidTrait;
use App\Modules\Reflector\Reflector;
use App\Repository\UserProfileRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ReflectionException;

/**
 * @ORM\Entity(repositoryClass=UserProfileRepository::class)
 */
class UserProfile
{
    /**
     * BirthDay Date format
     */
    public const BirthDayFormat = 'd/m/Y';


    use IdTrait;

    /**
     * @ORM\Column(type="uuid", length=255)
     */
    private ?string $userId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatar = '';


    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $email;


    /**
     * @ORM\Column(type="date")
     */
    private ?DateTime $birthDay;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country;


    /**
     * @ORM\OneToOne (targetEntity=User::class, mappedBy="profile",  cascade={"persist", "remove", "detach", "refresh"})
     * @var User|null
     */
    private ?User $user;

    use UpdatedTrait;
    use CreatedTrait;

    public function __construct()
    {
        if (isset($this->birthDay)) {
            $this->birthDay->format('d/m/Y');
        }
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

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


    public function getBirthDay(): DateTime
    {
        $this->birthDay->format('d/m/Y');
        return $this->birthDay;
    }

    public function setBirthDay(DateTime $birthDay): self
    {
        $birthDay->format('d/m/Y');
        $this->birthDay = $birthDay;

        return $this;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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

    /**
     * @throws ReflectionException
     */
    public function arrayToEntity(array $input): self {
        Reflector::arrayToEntity($this, $input);
        return $this;
    }
}
