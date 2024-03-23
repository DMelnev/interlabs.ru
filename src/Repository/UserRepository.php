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
//        $sql = 'SELECT * FROM user WHERE user.admin <> true OR user.admin IS NULL';
        $sql = 'SELECT * FROM user';
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

    public function deleteById(int $id): ?string
    {
        $sql = 'DELETE FROM user WHERE user.id = :id AND (user.admin IS NULL OR user.admin = false)';
        $params = ['id' => $id];
        $this->db->query($sql, $params);

        return $this->db->getError();
    }

    public function insert(array $data): ?string
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

    public function update(array $data): ?string
    {
        $params = [
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'] ?? '',
            'address' => $data['address'] ?? '',
        ];
        $sql = 'UPDATE user SET name = :name, email = :email, address = :address WHERE id = :id';

        $this->db->query($sql, $params);
        return $this->db->getError();
    }
}