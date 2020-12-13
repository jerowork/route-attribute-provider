<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Cache;

use Generator;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;
use JsonException;
use Psr\SimpleCache\CacheInterface;

final class CacheRouteLoaderDecorator implements RouteLoaderInterface
{
    use CreateCacheKeyTrait;
    use MapCachePayloadToLoadedRoutesTrait;
    use MapLoadedRoutesToCachePayloadTrait;

    private RouteLoaderInterface $routeLoader;
    private CacheInterface $cache;

    public function __construct(RouteLoaderInterface $routeLoader, CacheInterface $cache)
    {
        $this->routeLoader = $routeLoader;
        $this->cache       = $cache;
    }

    /**
     * @throws JsonException
     */
    public function load(string $className): Generator
    {
        $cacheKey = $this->createCacheKey($className);
        $payload  = $this->cache->get($cacheKey);

        if ($payload !== null) {
            yield from $this->mapCachePayloadToLoadedRoutes($payload);

            return;
        }

        $loadedRoutes = iterator_to_array($this->routeLoader->load($className));

        $this->cache->set($cacheKey, $this->mapLoadedRoutesToCachePayload(...$loadedRoutes));

        yield from $loadedRoutes;
    }
}
