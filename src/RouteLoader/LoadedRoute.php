<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader;

use Jerowork\RouteAttributeProvider\Api\Route;

final class LoadedRoute
{
    public function __construct(
        private string $className,
        private string $methodName,
        private Route $route
    ) {
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
