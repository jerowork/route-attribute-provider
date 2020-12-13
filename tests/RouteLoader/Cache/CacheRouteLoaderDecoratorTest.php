<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader\Cache;

use Generator;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteLoader\Cache\CacheRouteLoaderDecorator;
use Jerowork\RouteAttributeProvider\RouteLoader\Cache\CreateCacheKeyTrait;
use Jerowork\RouteAttributeProvider\RouteLoader\Cache\MapLoadedRoutesToCachePayloadTrait;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

final class CacheRouteLoaderDecoratorTest extends MockeryTestCase
{
    use CreateCacheKeyTrait;
    use MapLoadedRoutesToCachePayloadTrait;

    public function testItShouldGetLoadedRoutesFromInnerLoaderAndSetInCache(): void
    {
        $routeLoader = new CacheRouteLoaderDecorator(
            $innerRouteLoader = Mockery::mock(RouteLoaderInterface::class),
            $cache = new Psr16Cache(new ArrayAdapter())
        );

        $innerRouteLoader->expects('load')->andReturn($this->returnArrayAsGenerator([
            $loadedRoute = new LoadedRoute(StubClass::class, '__invoke', new Route('/root')),
        ]));

        $loadedRoutes = $routeLoader->load(StubClass::class);

        $this->assertSame([$loadedRoute], iterator_to_array($loadedRoutes));

        $this->assertEquals(
            $this->mapLoadedRoutesToCachePayload($loadedRoute),
            $cache->get($this->createCacheKey(StubClass::class))
        );
    }

    public function testItShouldGetLoadedRoutesFromCache(): void
    {
        $routeLoader = new CacheRouteLoaderDecorator(
            $innerRouteLoader = Mockery::mock(RouteLoaderInterface::class),
            $cache = new Psr16Cache(new ArrayAdapter())
        );

        $cache->set(
            $this->createCacheKey(StubClass::class),
            $this->mapLoadedRoutesToCachePayload(
                $loadedRoute = new LoadedRoute(StubClass::class, '__invoke', new Route('/root'))
            )
        );

        $innerRouteLoader->expects('load')->never();

        $loadedRoutes = $routeLoader->load(StubClass::class);

        $this->assertEquals([$loadedRoute], iterator_to_array($loadedRoutes));
    }

    private function returnArrayAsGenerator(array $data): Generator
    {
        yield from $data;
    }
}
