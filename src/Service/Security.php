<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use PDO;

class Security
{

    const USER_IDENTIFIER = 'user';
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }


    public function getUser(): ?User
    {
        $userId = (int)$_SESSION[self::USER_IDENTIFIER] ?? null;
        if (!$userId) return null;

        return $this->userRepository->getById($userId);
    }

    public function authorise(string $login, string $plainPassword): bool
    {
        $user = $this->userRepository->getByLogin($login);
        if (!$user) return false;

        if (password_verify($plainPassword, $user->getPassword())) {
            $_SESSION[self::USER_IDENTIFIER] = $user->getId();
            return true;
        }

        return false;
    }
    public function unAuthorise()
    {
        unset($_SESSION[self::USER_IDENTIFIER]);
    }
}