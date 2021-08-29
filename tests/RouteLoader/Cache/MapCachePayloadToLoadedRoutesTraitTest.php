<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader\Cache;

use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteLoader\Cache\MapCachePayloadToLoadedRoutesTrait;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use PHPUnit\Framework\TestCase;

final class MapCachePayloadToLoadedRoutesTraitTest extends TestCase
{
    public function testItShouldMapPayloadToLoadedRoutes(): void
    {
        $trait = new class () {
            use MapCachePayloadToLoadedRoutesTrait;
        };

        $loadedRoutes = $trait->mapCachePayloadToLoadedRoutes(
            file_get_contents(__DIR__ . '/../../resources/payload.json')
        );

        $this->assertEquals([
            new LoadedRoute(
                StubClass::class,
                '__invoke',
                new Route(
                    '/root',
                    RequestMethod::GET,
                    'root',
                    [StubClass4::class, StubClass3::class],
                    'localhost',
                    ['http'],
                    80,
                    null,
                    ['strategy' => 'something']
                )
            ),
            new LoadedRoute(
                StubClass2::class,
                '__invoke',
                new Route('/minimalist')
            )
        ], iterator_to_array($loadedRoutes));
    }
}
