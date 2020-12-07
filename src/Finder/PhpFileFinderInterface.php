<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Finder;

use Generator;

interface PhpFileFinderInterface
{
    /**
     * @return Generator<string>
     */
    public function find(string $directory): Generator;
}
