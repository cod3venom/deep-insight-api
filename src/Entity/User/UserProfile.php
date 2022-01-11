<?php

namespace App\Entity\User;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UserIdTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserProfileRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserProfileRepository::class)
 */
class UserProfile
{

    use IdTrait;

    /**
     * @ORM\Column(type="uuid", length=255)
     */
    private string $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private string $phone;

    /**
     * @ORM\Column(type="date")
     */
    private DateTime $birthDay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatar;

    use UpdatedTrait;
    use CreatedTrait;


    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function getBirthDay(): DateTime
    {
        return $this->birthDay;
    }

    public function setBirthDay(DateTime $birthDay): self
    {
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

    public function arrayToProfile(array $input): self {
        foreach ($input as $k=>$v){
            $this->{$k} = $v;
        }
        return $this;
    }
}
