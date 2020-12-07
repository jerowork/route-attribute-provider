<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader\Reflection;

use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\RouteLoader\Reflection\ReflectionRouteLoader;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ReflectionRouteLoaderTest extends TestCase
{
    public function testItShouldGetMinimalistLoadedRoute(): void
    {
        $loader       = new ReflectionRouteLoader();
        $loadedRoutes = iterator_to_array($loader->load(StubClass::class));

        $this->assertCount(1, $loadedRoutes);

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[0];

        $this->assertSame(StubClass::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/minimalist', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::GET], $loadedRoute->getRoute()->getMethods());
        $this->assertNull($loadedRoute->getRoute()->getName());
        $this->assertSame([], $loadedRoute->getRoute()->getMiddleware());
    }

    public function testItShouldGetExtendedLoadedRoute(): void
    {
        $loader       = new ReflectionRouteLoader();
        $loadedRoutes = iterator_to_array($loader->load(StubClass2::class));

        $this->assertCount(1, $loadedRoutes);

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[0];

        $this->assertSame(StubClass2::class, $loadedRoute->getClassName());
        $this->assertSame('handle', $loadedRoute->getMethodName());
        $this->assertSame('/extended', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::POST], $loadedRoute->getRoute()->getMethods());
        $this->assertSame('extended.route', $loadedRoute->getRoute()->getName());
        $this->assertSame([StubClass4::class], $loadedRoute->getRoute()->getMiddleware());
    }

    public function testItShouldGetFullFledgedLoadedRoute(): void
    {
        $loader       = new ReflectionRouteLoader();
        $loadedRoutes = iterator_to_array($loader->load(StubClass3::class));

        $this->assertCount(2, $loadedRoutes);

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[0];

        $this->assertSame(StubClass3::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/full-fledged', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::POST, RequestMethod::PUT], $loadedRoute->getRoute()->getMethods());
        $this->assertSame('full-fledged.route', $loadedRoute->getRoute()->getName());
        $this->assertSame([StubClass4::class, stdClass::class], $loadedRoute->getRoute()->getMiddleware());

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[1];

        $this->assertSame(StubClass3::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/alternative-second', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::DELETE, RequestMethod::OPTIONS], $loadedRoute->getRoute()->getMethods());
        $this->assertSame('alternative-second.route', $loadedRoute->getRoute()->getName());
        $this->assertSame([], $loadedRoute->getRoute()->getMiddleware());
    }
}
