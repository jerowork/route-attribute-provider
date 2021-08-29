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

    public function __construct(private RouteLoaderInterface $routeLoader, private CacheInterface $cache)
    {
    }

    public function getDirectories() : array
    {
        return $this->routeLoader->getDirectories();
    }

    public function addDirectory(string ...$directories) : self
    {
        $this->routeLoader->addDirectory(...$directories);

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function getRoutes() : Generator
    {
        $cacheKey = $this->createCacheKey($this->getDirectories());
        $payload  = $this->cache->get($cacheKey);

        if ($payload !== null) {
            yield from $this->mapCachePayloadToLoadedRoutes($payload);

            return;
        }

        $loadedRoutes = iterator_to_array($this->routeLoader->getRoutes());

        $this->cache->set($cacheKey, $this->mapLoadedRoutesToCachePayload(...$loadedRoutes));

        yield from $loadedRoutes;
    }
}
