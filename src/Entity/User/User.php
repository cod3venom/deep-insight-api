<?php

namespace App\Entity\User;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
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

    use IdTrait;

    /**
     * @ORM\Column(type="uuid", unique=true)
     */
    private string $userId;

    /**
     * @ORM\Column(type="string", length=255)
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

    use UpdatedTrait;
    use CreatedTrait;


    public function setUserId($userId): self
    {
        $this->userId = $userId;

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

    public function getUsername(): ?string
    {
        return null;
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
