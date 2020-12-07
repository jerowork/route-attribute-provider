<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader;

use Generator;

interface RouteLoaderInterface
{
    /**
     * @param class-string $className
     *
     * @return Generator<LoadedRoute>
     */
    public function load(string $className): Generator;
}
