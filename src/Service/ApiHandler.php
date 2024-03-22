<?php

namespace App\Service;

use App\Repository\UserRepository;

class ApiHandler
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }


    public function deleteUsers(array $data)
    {
        foreach ($data as $id) {
            $this->userRepository->deleteUserById((int)$id);
        }

    }

    public function addUser(array $data): array
    {
        $result = $this->validateUserData($data);
        if ($result['messages']) return $result;
        $error = $this->userRepository->addUser($data);
        if ($error) {
            $result['messages']['unknown'] = $error;
            $result['status'] = 'error';
        }

        return $result;
    }

    private function validateUserData(array $data): array
    {
        $result = [];
        if (!$data['name']) $result['messages']['name'] = 'Введите имя';
        else {
            if (mb_strlen($data['name']) < 2) $result['messages']['name'] = 'Имя должно быть длиннее';
            if (mb_strlen($data['name']) > 255) $result['messages']['name'] = 'Имя должно быть короче 256 символов';
        }
        if (!$data['login']) $result['messages']['login'] = 'Введите логин';
        else {
            if (mb_strlen($data['login']) < 5) $result['messages']['login'] = 'Логин должен быть длиннее 4х символов';
            if (mb_strlen($data['login']) > 255) $result['messages']['login'] = 'Логин должен быть короче 256 символов';
            if (!$result['messages']['login']) {
                if ($this->userRepository->getByLogin($data['login'])) $result['messages']['login'] = 'Указанный логин уже занят.';
            }
        }
        if (!$data['password']) $result['messages']['password'] = 'Введите пароль';
        else {
            if (mb_strlen($data['name']) < 7) $result['messages']['password'] = 'Пароль должен быть длиннее 6х символов';
            if (mb_strlen($data['name']) > 255) $result['messages']['password'] = 'Пароль должен быть короче 256 символов';
        }
        if ($data['email']) {
            if (strlen($data['email']) > 255) $result['messages']['email'] = 'E-mail должен быть короче 256 символов';
            if (!preg_match('/^[a-z0-9]+[a-z0-9.\-_]*[a-z0-9]+@[a-z0-9]{1,127}[a-z0-9.\-_]*[a-z0-9]{1,127}\.\w{2,4}$/i', $data['email']))
                $result['messages']['email'] = 'Не корректный e-mail';
        }
        if ($data['address'] && strlen($data['email']) > 255) $result['messages']['address'] = 'Адрес должен быть короче 256 символов';

        $data['status'] = $data['messages'] ? 'error' : 'success';
        return $data;
    }


}