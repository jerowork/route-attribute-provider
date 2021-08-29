<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader;

use Generator;

interface RouteLoaderInterface
{
    public function addDirectory(string ...$directories): self;

    /**
     * @return string[]
     */
    public function getDirectories(): array;

    /**
     * @return Generator<LoadedRoute>
     */
    public function getRoutes(): Generator;
}
