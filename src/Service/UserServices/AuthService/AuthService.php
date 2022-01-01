<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 01.01.2022
 * Time: 00:29
*/


declare(strict_types=1);

namespace App\Service\UserServices\AuthService;

use App\Entity\User\User;
use App\Repository\UserRepository;
use App\Service\UserServices\AuthService\Exceptions\UserNotFoundException;
use App\Service\UserServices\AuthService\Exceptions\WrongPasswordException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * Class User
 * @package App\Service
 */
final class AuthService
{
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $tokenManager;
    public function __construct(
        UserRepository $userRepository,
        JWTTokenManagerInterface $tokenManager
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws UserNotFoundException
     * @throws WrongPasswordException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function authenticate(string $email,string $password): User {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Wrong E-mail format");
        }
        if (empty($password)) {
            throw new InvalidArgumentException("Password can't be empty");
        }
        if (strlen($password) < 5) {
            throw new InvalidArgumentException("Password must contain at least 5 characters");
        }

        $user = $this->userRepository->findByEmail($email);

        if (is_null($user->getId())) {
            throw new UserNotFoundException("Wrong E-mail or Password");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new WrongPasswordException("Wrong E-mail or Password");
        }

        $user->setJwt($this->tokenManager->create($user));
        $this->userRepository->save($user);
        return $user;
    }
}