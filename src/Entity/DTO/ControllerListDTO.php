<?php

namespace App\Entity\DTO;

class ControllerListDTO
{
    private static array $list;

    static public function addData(ControllerDataDTO $dataDTO)
    {
        self::$list[] = $dataDTO;

    }

    static public function getList(): array
    {
        return self::$list;
    }
}