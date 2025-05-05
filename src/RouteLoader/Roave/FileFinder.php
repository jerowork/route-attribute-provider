<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Roave;

interface FileFinder
{
    /**
     * @return list<string>
     */
    public function getFiles(string ...$directories) : array;
}
