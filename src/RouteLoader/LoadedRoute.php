<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader;

use Jerowork\RouteAttributeProvider\Api\Route;

final class LoadedRoute
{
    private string $className;
    private string $methodName;
    private Route $route;

    public function __construct(string $className, string $methodName, Route $route)
    {
        $this->className  = $className;
        $this->methodName = $methodName;
        $this->route      = $route;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }
}
