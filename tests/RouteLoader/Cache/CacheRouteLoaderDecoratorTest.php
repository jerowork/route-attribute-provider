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

        $innerRouteLoader->allows('addDirectory')
            ->with(__DIR__ . '/../../resources/directory/sub')
            ->andReturn($routeLoader);

        $innerRouteLoader->allows('getDirectories')
            ->andReturn([__DIR__ . '/../../resources/directory/sub']);

        $innerRouteLoader->expects('getRoutes')->andReturn($this->returnArrayAsGenerator([
            $loadedRoute = new LoadedRoute(StubClass::class, '__invoke', new Route('/root')),
        ]));

        $loadedRoutes = $routeLoader->addDirectory(__DIR__ . '/../../resources/directory/sub')->getRoutes();

        $this->assertSame([$loadedRoute], iterator_to_array($loadedRoutes));

        $this->assertEquals(
            $this->mapLoadedRoutesToCachePayload($loadedRoute),
            $cache->get($this->createCacheKey([__DIR__ . '/../../resources/directory/sub']))
        );
    }

    public function testItShouldGetLoadedRoutesFromCache(): void
    {
        $routeLoader = new CacheRouteLoaderDecorator(
            $innerRouteLoader = Mockery::mock(RouteLoaderInterface::class),
            $cache = new Psr16Cache(new ArrayAdapter())
        );

        $innerRouteLoader->allows('addDirectory')
            ->with(__DIR__ . '/../../resources/directory/sub')
            ->andReturn($routeLoader);

        $innerRouteLoader->allows('getDirectories')
            ->andReturn([__DIR__ . '/../../resources/directory/sub']);

        $cache->set(
            $this->createCacheKey([__DIR__ . '/../../resources/directory/sub']),
            $this->mapLoadedRoutesToCachePayload(
                $loadedRoute = new LoadedRoute(StubClass::class, '__invoke', new Route('/root'))
            )
        );

        $innerRouteLoader->expects('getRoutes')->never();

        $loadedRoutes = $routeLoader->addDirectory(__DIR__ . '/../../resources/directory/sub')->getRoutes();

        $this->assertEquals([$loadedRoute], iterator_to_array($loadedRoutes));
    }

    private function returnArrayAsGenerator(array $data): Generator
    {
        yield from $data;
    }
}
