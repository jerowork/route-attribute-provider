<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Cache;

trait CreateCacheKeyTrait
{
    private string $cacheKeyPrefix = 'route_attribute_provider.route_loader';

    public function createCacheKey(string $className): string
    {
        return sprintf(
            '%s.%s',
            $this->cacheKeyPrefix,
            str_replace('\\', '.', $className)
        );
    }
}
