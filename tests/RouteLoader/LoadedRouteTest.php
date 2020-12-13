<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader;

use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use PHPUnit\Framework\TestCase;
use stdClass;

final class LoadedRouteTest extends TestCase
{
    public function testItShouldConstructLoadedRoute(): void
    {
        $loadedRoute = new LoadedRoute(
            stdClass::class,
            '__invoke',
            $route = new Route('/')
        );

        $this->assertSame(stdClass::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame($route, $loadedRoute->getRoute());
    }

    public function testItShouldSerializeToArray(): void
    {
        $loadedRoute = new LoadedRoute(
            stdClass::class,
            '__invoke',
            $route = new Route('/')
        );

        $this->assertSame([
            'className'  => stdClass::class,
            'methodName' => '__invoke',
            'route'      => $route->jsonSerialize(),
        ], $loadedRoute->jsonSerialize());
    }

    public function testItShouldConstructFromPayload(): void
    {
        $route = new Route('/');

        $loadedRoute = LoadedRoute::fromPayload([
            'className'  => stdClass::class,
            'methodName' => '__invoke',
            'route'      => $route->jsonSerialize(),
        ]);

        $this->assertSame(stdClass::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertEquals($route, $loadedRoute->getRoute());
    }
}
