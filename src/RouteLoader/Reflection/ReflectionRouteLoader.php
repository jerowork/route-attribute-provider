<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Reflection;

use Generator;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;
use ReflectionClass;
use ReflectionException;

final class ReflectionRouteLoader implements RouteLoaderInterface
{
    public function load(string $className): Generator
    {
        $reflectionClass = new ReflectionClass($className);

        foreach ($reflectionClass->getMethods() as $method) {
            foreach ($method->getAttributes(Route::class) as $attribute) {
                /** @var Route $route */
                $route = $attribute->newInstance();

                yield new LoadedRoute($reflectionClass->getName(), $method->getName(), $route);
            }
        }
    }
}
