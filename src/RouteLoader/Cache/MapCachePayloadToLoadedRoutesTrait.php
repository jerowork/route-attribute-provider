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
    public function mapCachePayloadToLoadedRoutes(string $payload) : Generator
    {
        /**
         * @var list<array{
         *      className: string,
         *      methodName: string,
         *      route: array{
         *       pattern: string,
         *       methods: string|list<string>,
         *       name: null|string,
         *       middleware: string|list<string>,
         *       host: null|string,
         *       schemes: string|list<string>,
         *       httpPort: null|int,
         *       httpsPort: null|int,
         *       options: array<string, mixed>
         *   }
         *  }> $loadedRoutesPayload
         */
        $loadedRoutesPayload = (array) json_decode($payload, true, flags: JSON_THROW_ON_ERROR);

        yield from array_map(
            static fn (array $payload) : LoadedRoute => LoadedRoute::fromPayload($payload),
            $loadedRoutesPayload,
        );
    }
}
