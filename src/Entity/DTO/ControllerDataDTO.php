<?php

namespace App\Entity\DTO;

use ReflectionMethod;

class ControllerDataDTO
{
    private string $name;
    private string $route;
    private string $httpMethod;
    private string $class;
    private ReflectionMethod $method;
    private array $params=[];

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function setHttpMethod(string $httpMethod): self
    {
        $this->httpMethod = $httpMethod;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function addParam($param): self
    {
        if ($param) $this->params[] = $param;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getMethod(): ReflectionMethod
    {
        return $this->method;
    }

    public function setMethod(\ReflectionMethod $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

}