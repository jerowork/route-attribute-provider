<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Cache;

use Generator;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use JsonException;

trait MapCachePayloadToLoadedRoutesTrait
{
    /**
     * @throws JsonException
     *
     * @return Generator<LoadedRoute>
     */
    public function mapCachePayloadToLoadedRoutes(mixed $payload) : Generator
    {
        yield from array_map(
            static fn (array $payload) : LoadedRoute => LoadedRoute::fromPayload($payload),
            json_decode($payload, true, flags: JSON_THROW_ON_ERROR)
        );
    }
}
