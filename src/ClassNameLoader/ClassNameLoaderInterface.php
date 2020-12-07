<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\ClassNameLoader;

use Generator;

interface ClassNameLoaderInterface
{
    /**
     * @return Generator<class-string>
     */
    public function load(string $directory): Generator;
}
