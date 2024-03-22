<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\DbHandler;

class UserRepository
{
    private DbHandler $db;

    public function __construct()
    {
        $this->db = new DbHandler();
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM user WHERE user.admin <> true OR user.admin IS NULL';
        $params = [];
        return $this->db->query($sql, $params, User::class);
    }

    public function getById(int $id): ?User
    {
        $sql = 'SELECT * FROM user WHERE user.id = :id';
        $params = ['id' => $id];
        /** @var User $user */
        $user = $this->db->query($sql, $params, User::class);

        return $user ? $user[0] : null;
    }

    public function getByLogin(string $login): ?User
    {
        $sql = 'SELECT * FROM user WHERE user.login = :login';
        $params = ['login' => $login];
        /** @var User $user */
        $user = $this->db->query($sql, $params, User::class);

        return $user ? $user[0] : null;
    }

    public function deleteUserById(int $id): ?string
    {
        $sql = 'DELETE FROM user WHERE user.id = :id';
        $params = ['id' => $id];
        $this->db->query($sql, $params);

        return $this->db->getError();
    }

    public function addUser(array $data): ?string
    {
        $params = [
            'name' => $data['name'],
            'login' => $data['login'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'email' => $data['email'] ?? '',
            'address' => $data['address'] ?? '',
        ];
        $sql = 'INSERT INTO user (login, name, email, address, password) 
    VALUES  (:login, :name, :email, :address, :password)';

        $this->db->query($sql, $params);
        return $this->db->getError();
    }
}