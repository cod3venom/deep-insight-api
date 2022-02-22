<?php

namespace App\Entity\User;

use App\Entity\Contact\ContactProfile;
use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_SUB_USER = 'ROLE_SUB_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public array $rolesList = [
        self::ROLE_USER,
        self::ROLE_SUB_USER,
        self::ROLE_ADMIN,
    ];

    private string $token = "";

    use IdTrait;

    /**
     * @ORM\Column(type="uuid")
     */
    private string $userId = "";

    /**

     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $pwdRecoveryToken;

    /**

     * @ORM\Column(type="array")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     * @var DateTimeInterface
     */
    private DateTimeInterface $lastLoginAt;

    /**
     * @Groups ({"profile"})
     * @ORM\OneToOne (targetEntity=UserProfile::class, inversedBy="user",  cascade={"persist", "remove", "detach", "refresh"})
     * @var UserProfile|null
     */
    public ?UserProfile $profile;





    use UpdatedTrait;
    use CreatedTrait;

    public function __construct()
    {
        $this->profile = new UserProfile();
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPwdRecoveryToken(): ?string
    {
        return $this->pwdRecoveryToken;
    }

    public function setPwdRecoveryToken(?string $pwdRecoveryToken): self
    {
        $this->pwdRecoveryToken = $pwdRecoveryToken;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLastLoginAt(): DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(): self
    {
        $this->lastLoginAt  = new DateTime();

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getJwt(): string
    {
        return $this->token;
    }

    /**
     * @param string $jwt
     * @return User
     */
    public function setJwt(string $jwt): self
    {
        $this->token = $jwt;
        return $this;
    }


    public function getSalt(): string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
