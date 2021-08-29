<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Cache;

use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use JsonException;

trait MapLoadedRoutesToCachePayloadTrait
{
    /**
     * @throws JsonException
     */
    public function mapLoadedRoutesToCachePayload(LoadedRoute ...$loadedRoutes) : string
    {
        return json_encode(array_map(
            static fn (LoadedRoute $loadedRoute) : array => $loadedRoute->jsonSerialize(),
            $loadedRoutes
        ), JSON_THROW_ON_ERROR);
    }
}
