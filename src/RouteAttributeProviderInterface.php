<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider;

use Jerowork\RouteAttributeProvider\Api\Route;

interface RouteAttributeProviderInterface
{
    public function configure(string $className, string $methodName, Route $route): void;
}
