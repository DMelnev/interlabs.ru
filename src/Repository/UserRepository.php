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

    public function getAll():array
    {
        $sql = 'SELECT * FROM user WHERE user.admin <> true OR user.admin IS NULL';
        $params = [];
        return $this->db->query($sql, $params, User::class);
    }
    public function getById(int $id):?User
    {
        $sql = 'SELECT * FROM user WHERE user.id = :id';
        $params = ['id'=>$id];
        /** @var User $user */
        $user = $this->db->query($sql, $params, User::class);

        return $user ? $user[0] : null;
    }
    public function getByLogin(string $login):?User
    {
        $sql = 'SELECT * FROM user WHERE user.login = :login';
        $params = ['login'=>$login];
        /** @var User $user */
        $user = $this->db->query($sql, $params, User::class);

        return $user ? $user[0] : null;
    }
}