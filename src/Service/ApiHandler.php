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

    public function deleteUsers(array $data): array
    {
        $result = [];
        foreach ($data as $id) {
            if ($this->userRepository->getById($id)->isAdmin()) $result['messages']['admin'][$id] = 'Вы не можете удалить администратора!';
            else {
                $error = $this->userRepository->deleteById($id);
                if ($error) {
                    $result['messages']['unknown'][$id] = $error;
                } else {
                    $result['data'][] = $id;
                }
            }
        }
        $result['status'] = $result['messages'] ? 'error' : 'success';

        return $result;
    }

    public function addUser(array $data): array
    {
        $data = $this->trimArray($data);
        $result = array_merge($this->validateAddData($data), $this->validateEditData($data));

        if (!$result['messages']) {
            $error = $this->userRepository->insert($data);
            if ($error) {
                $result['messages']['unknown'] = $error;
            } else {
                $user = $this->userRepository->getByLogin($data['login']);
                $result['data'] = $data;
                $result['data']['id'] = $user->getId();
            }
        }
        $result['status'] = $result['messages'] ? 'error' : 'success';
        return $result;
    }

    public function editUser(array $data): array
    {
        $data = $this->trimArray($data);
        $result = $this->validateEditData($data);

        if (!$result['messages']) {
            $error = $this->userRepository->update($data);
            if ($error) {
                $result['messages']['unknown'] = $error;
            } else {
                $result['data'] = $data;
            }
        }
        $result['status'] = $result['messages'] ? 'error' : 'success';
        return $result;
    }

    private function trimArray(array $data): array
    {
        return array_map(function ($e) {
            return trim($e);
        }, $data);
    }

    private function validateAddData(array $data): array
    {
        $result = [];
        if (!$data['login']) $result['messages']['login'] = 'Введите логин';
        else {
            if (mb_strlen($data['login']) <= 4) $result['messages']['login'] = 'Логин должен быть длиннее 4х символов';
            if (mb_strlen($data['login']) > 255) $result['messages']['login'] = 'Логин должен быть короче 256 символов';
            if (!$result['messages']['login']) {
                if ($this->userRepository->getByLogin($data['login'])) $result['messages']['login'] = 'Указанный логин уже занят.';
            }
        }
        if (!$data['password']) $result['messages']['password'] = 'Введите пароль';
        else {
            if (mb_strlen($data['password']) <= 5) $result['messages']['password'] = 'Пароль должен быть длиннее 5 символов';
            if (mb_strlen($data['password']) > 255) $result['messages']['password'] = 'Пароль должен быть короче 256 символов';
        }
        return $result;
    }

    private function validateEditData(array $data): array
    {

        $result = [];
        if (!$data['name']) $result['messages']['name'] = 'Введите имя';
        else {
            if (!preg_match('/^[a-zа-я]+[\bа-я\s\-~!@#$%^&*()+=_;:{},.\[\]]*/i', $data['name'])) $result['messages']['name'] = 'В имени не допустимые символы';
            if (mb_strlen($data['name']) < 2) $result['messages']['name'] = 'Очень короткое имя';
            if (mb_strlen($data['name']) > 255) $result['messages']['name'] = 'Имя должно быть короче 256 символов';
        }
        if ($data['email']) {
            if (strlen($data['email']) > 255) $result['messages']['email'] = 'E-mail должен быть короче 256 символов';
            if (!preg_match('/^[a-z0-9]+[a-z0-9.\-_]*[a-z0-9]+@[a-z0-9]{1,127}[a-z0-9.\-_]*[a-z0-9]{1,127}\.\w{2,4}$/i', $data['email']))
                $result['messages']['email'] = 'Не корректный e-mail';
        }
        if ($data['address'] && strlen($data['address']) > 255) $result['messages']['address'] = 'Адрес должен быть короче 256 символов';

        return $result;
    }


}