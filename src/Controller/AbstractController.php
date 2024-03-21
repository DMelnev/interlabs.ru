<?php

namespace App\Controller;

abstract class AbstractController
{
    public function render(string $name, array $parameters)
    {
        $path = dirname(__DIR__, 2) . $name;
        if (!file_exists($path)) return $this->httpError("500 Error template path!");

        foreach ($parameters as $key => $parameter) {
            $$key = $parameter;
        }
        include_once $path;
    }

    public function httpError(string $message): string
    {
        header($_SERVER["SERVER_PROTOCOL"] . ' ' . $message);

        return $message;
    }
}