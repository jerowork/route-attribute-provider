<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\Api;

use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use PHPUnit\Framework\TestCase;
use stdClass;

final class RouteTest extends TestCase
{
    public function testItShouldConstructMinimalistRoute(): void
    {
        $route = new Route('/minimalist');

        $this->assertSame('/minimalist', $route->getPattern());
        $this->assertSame([RequestMethod::GET], $route->getMethods());
        $this->assertNull($route->getName());
        $this->assertSame([], $route->getMiddleware());
    }

    public function testItShouldConstructExtendedRoute(): void
    {
        $route = new Route('/extended', RequestMethod::PUT, 'extended.route', stdClass::class);

        $this->assertSame('/extended', $route->getPattern());
        $this->assertSame([RequestMethod::PUT], $route->getMethods());
        $this->assertSame('extended.route', $route->getName());
        $this->assertSame([stdClass::class], $route->getMiddleware());
    }

    public function testItShouldConstructFullFledgedRoute(): void
    {
        $route = new Route(
            '/full-fledged',
            [RequestMethod::GET, RequestMethod::POST],
            'full-fledged.route',
            [stdClass::class, stdClass::class]
        );

        $this->assertSame('/full-fledged', $route->getPattern());
        $this->assertSame([RequestMethod::GET, RequestMethod::POST], $route->getMethods());
        $this->assertSame('full-fledged.route', $route->getName());
        $this->assertSame([stdClass::class, stdClass::class], $route->getMiddleware());
    }

    public function testItShouldSerializeToArray(): void
    {
        $route = new Route(
            '/full-fledged',
            [RequestMethod::GET, RequestMethod::POST],
            'full-fledged.route',
            [stdClass::class, stdClass::class],
            'localhost',
            'http',
            80,
            null,
            ['strategy' => 'something']
        );

        $this->assertSame([
            'pattern'    => '/full-fledged',
            'methods'    => [RequestMethod::GET, RequestMethod::POST],
            'name'       => 'full-fledged.route',
            'middleware' => [stdClass::class, stdClass::class],
            'host'       => 'localhost',
            'schemes'    => ['http'],
            'httpPort'   => 80,
            'httpsPort'  => null,
            'options'    => ['strategy' => 'something'],
        ], $route->jsonSerialize());
    }

    public function testItShouldConstructFromPayload(): void
    {
        $route = Route::fromPayload([
            'pattern'    => '/full-fledged',
            'methods'    => [RequestMethod::GET, RequestMethod::POST],
            'name'       => 'full-fledged.route',
            'middleware' => [stdClass::class, stdClass::class],
            'host'       => 'localhost',
            'schemes'    => ['http', 'https'],
            'httpPort'   => 80,
            'httpsPort'  => 443,
            'options'    => ['strategy' => 'something'],
        ]);

        $this->assertSame('/full-fledged', $route->getPattern());
        $this->assertSame([RequestMethod::GET, RequestMethod::POST], $route->getMethods());
        $this->assertSame('full-fledged.route', $route->getName());
        $this->assertSame([stdClass::class, stdClass::class], $route->getMiddleware());
        $this->assertSame('localhost', $route->getHost());
        $this->assertSame(['http', 'https'], $route->getSchemes());
        $this->assertSame(80, $route->getHttpPort());
        $this->assertSame(443, $route->getHttpsPort());
        $this->assertSame(['strategy' => 'something'], $route->getOptions());
    }
}
