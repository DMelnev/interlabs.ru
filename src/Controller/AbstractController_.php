<?php

namespace App\Controller;

use App\Core\Core;
use App\Entity\DTO\ControllerDataDTO;
use App\Entity\DTO\ControllerListDTO;
use App\Service\Security;

abstract class AbstractController_
{

    protected Security $security;

    public function __construct()
    {
        $this->security = new Security();
    }

    protected function render(string $templateName, array $parameters)
    {
        $path = dirname(__DIR__, 2) . $templateName;
        if (!file_exists($path)) return $this->httpError("500", " Error template path!");

        foreach ($parameters as $key => $parameter) {
            $$key = $parameter;
        }
        include_once $path;
    }

    protected function renderJson(array $data)
    {
        header('Content-Type: application/json; charset=utf-8');

        return json_encode($data);
    }
    protected function renderError(string $message)
    {
        return $this->renderJson(['status' => 'error', 'messages' => ['unknown' => $message]]);
    }

    protected function httpError(int $code, string $message): string
    {
        header($_SERVER["SERVER_PROTOCOL"] . ' ' . $code);

        return $message;
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url);
        return null;
    }

    protected function redirectToRoute(string $route, array $params = [])
    {
        $this->sessionWriteLastRoute($route);

        /** @var ControllerDataDTO $controller */
        foreach (ControllerListDTO::getList() as $controller) {
            if ($controller->getName() === $route) {
                return $this->redirect($this->getLink($controller, $params));
            }
        }
        return $this->httpError('500', 'Incorrect Controller Name!');
    }

    protected function redirectToLast(string $defaultRoute = null)
    {
        if ($_SESSION[Core::LAST_ROUTE_NAME]) {
            $this->redirectToRoute($_SESSION[Core::LAST_ROUTE_NAME], $_SESSION[Core::LAST_PARAMS_NAME]);
            unset($_SESSION[Core::LAST_ROUTE_NAME]);
            unset($_SESSION[Core::LAST_PARAMS_NAME]);
        } else {
            $this->redirectToRoute($defaultRoute ?: Core::DEFAULT_ROUTE_NAME);
        }
        return null;
    }

    private function getLink(ControllerDataDTO $dataDTO, array $params): string
    {
        $link = $dataDTO->getRoute();

        foreach ($params as $name => $value) {
            $pattern = "/\{" . $name . "}/";
            $link = preg_replace($pattern, $value, $link);
        }

        return $link;
    }

    private function sessionWriteLastRoute(string $route)
    {
        if (
            $route == Core::LOGIN_ROUTE_NAME
            && $_SESSION[Core::CURRENT_ROUTE_NAME] != Core::LOGIN_ROUTE_NAME
            && $_SESSION[Core::CURRENT_ROUTE_NAME] != Core::LOGOUT_ROUTE_NAME
        ) {
            $_SESSION[Core::LAST_ROUTE_NAME] = $_SESSION[Core::CURRENT_ROUTE_NAME] ?? '';
            $_SESSION[Core::LAST_PARAMS_NAME] = $_SESSION[Core::CURRENT_PARAMS_NAME] ?? [];
        }
    }

}