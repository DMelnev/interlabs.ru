<?php

namespace App\Service;
class FileHandler
{
    protected function readFile(string $filename): ?string
    {
        return file_get_contents(dirname(__DIR__, 2) . $filename);
    }

    protected function putFile(string $filename, $data)
    {
        return file_put_contents(dirname(__DIR__, 2) . $filename, $data);
    }
}