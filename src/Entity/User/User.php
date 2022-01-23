<?php

namespace App\Entity\User;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_SUB_USER = 'ROLE_SUB_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    private string $token = "";

    use IdTrait;

    /**
     * @ORM\Column(type="uuid")
     */
    private string $userId = "";

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    private ?string $userAuthorId;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $pwdRecoveryToken;

    /**
     * @ORM\Column(type="array")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @var DateTimeInterface
     */
    private DateTimeInterface $lastLoginAt;

    /**
     * @ORM\OneToOne (targetEntity=UserProfile::class, inversedBy="user",  cascade={"persist", "remove", "detach", "refresh"})
     * @var UserProfile|null
     */
    public ?UserProfile $profile;

    /**
     * @ORM\OneToOne (targetEntity=UserCompanyInfo::class, inversedBy="user",  cascade={"persist", "remove", "detach", "refresh"})
     * @var UserCompanyInfo|null
     */
    public ?UserCompanyInfo $company;

    use UpdatedTrait;
    use CreatedTrait;

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserAuthorId(): ?string
    {
        return $this->userAuthorId;
    }

    public function setUserAuthorId(?string $userAuthorId): self
    {
        $this->userAuthorId = $userAuthorId;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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
