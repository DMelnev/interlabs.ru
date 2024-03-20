<?php

namespace App\Core;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RegexIterator;

class Core
{
    const CONTROLLER_NAMESPACE = "App\\Controller\\";

    public function start()
    {
        $controllers = $this->getControllers();
        if (!$controllers) {
            header($_SERVER["SERVER_PROTOCOL"] . "404 Not Found");
            return '';
        }

        $uri = $_SERVER["REQUEST_URI"];
        $serverMethod = $_SERVER["REQUEST_METHOD"];

        foreach ($controllers as $controller) {
            $class = self::CONTROLLER_NAMESPACE . $controller;
            $methods = (new ReflectionClass($class))->getMethods();

            $resultMethod = null;
            $data = [];
            foreach ($methods as $method) {
                $doc = $method->getDocComment();
                if ($method->isPublic() && preg_match("/\*\s*@Route\s*\(/", $doc)) {
                    $data = $this->parseDoc($doc);
                    if ($this->matchRoute($uri, $data['route'])) {
                        if ((isset($data['method']) && $data['method'] == $serverMethod) || !isset($data['method'])) {
                            $resultMethod = $method;
                            break;
                        }
                    }
                }
            }
            if ($resultMethod) {
                $execute = new $class;
                $func = $resultMethod->getName();
                $params = $this->getRouteParams($uri, $data['route']);
                if ($resultMethod->getParameters()[0]->getType()->getName() == 'int') $params[0] = (int)$params[0];
                return $execute->$func($params[0]); // хардкод для одного параметра
            }
        }
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }

    private
    function matchRoute(string $uri, string $route): bool
    {
        $routePattern = preg_replace('/\//', '\/', $route);
        $routePattern = '/^' . preg_replace("/\{(.*)}/", '[a-z0-9\s\-\.]+', $routePattern) . '$/';

        return preg_match($routePattern, $uri);
    }

    private function getRouteParams(string $uri, string $route): array//реализовано для одного параметра
    {
        preg_match("/\{(.*)}/", $route, $arr);
        if (isset($arr[0])) {
            $patternArr = explode($arr[0], $route);
            $name = preg_replace('/[\{}]/', '', $arr[0]);//понадобится для нескольких параметров
            $patternArr[0] = preg_replace('/\//', '\/', $patternArr[0]);
            $patternArr[1] = preg_replace('/\//', '\/', $patternArr[1]);
            $pattern = '/^' . $patternArr[0] . '(.*)' . $patternArr[1] . '$/';
            preg_match($pattern, $uri, $arr2);
            if (isset($arr2[0])) {
                $var = $arr2[0];
                if ($patternArr[0]) $var = preg_replace('/^' . $patternArr[0] . '/', '', $var);
                if ($patternArr[1]) $var = preg_replace('/' . $patternArr[1] . '$/', '', $var);
                return [0 => $var];
            }
        }
        return [];
    }

    private function parseDoc($docs): array
    {
        preg_match_all("/\*\s*@\s*Route\s*\((.+)\)\s*\*/", $docs, $string);
        if (!$string) return [];
        $string = $string[0][0] ?? '';
        $string = preg_replace("/^\*\s*@\s*Route\s*\(/", '', $string);
        $string = preg_replace("/\)\s*\*$/", '', $string);
        $string = preg_replace("/[\s\"]/", '', $string);
        $array = explode(',', $string);
        $result['route'] = '';
        for ($i = 0; $i < count($array); $i++) {
            if ($i === 0) {
                preg_match_all("/\{(.+)}/", $array[$i], $res);
                $result['route'] = $array[$i];
            } else {
                $tempArr = explode('=', $array[$i]);
                if (count($tempArr) == 2) {
                    $result[$tempArr[0]] = $tempArr[1];
                }
            }
        }
        return $result;
    }

    private function getControllers(): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__, 2)));
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);
        $controllers = [];
        foreach ($regex as $file => $value) {
            $current = $this->parseTokens(token_get_all(file_get_contents(str_replace('\\', '/', $file))));
            if ($current !== false) {
                list($namespace, $class) = $current;
                if ($namespace === self::CONTROLLER_NAMESPACE) {
                    $controllers[] = $class;
                }
            }
        }
        return $controllers;
    }

    private function parseTokens(array $tokens)
    {
        $nsStart = false;
        $classStart = false;
        $namespace = '';
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                $classStart = true;
            }
            if ($classStart && $token[0] === T_STRING) {
                return [$namespace, $token[1]];
            }
            if ($token[0] === T_NAMESPACE) {
                $nsStart = true;
            }
            if ($nsStart && $token[0] === ';') {
                $nsStart = false;
            }
            if ($nsStart && $token[0] === T_STRING) {
                $namespace .= $token[1] . '\\';
            }
        }

        return false;
    }
}