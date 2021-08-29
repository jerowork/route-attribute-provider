<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Cache;

trait CreateCacheKeyTrait
{
    private string $cacheKeyPrefix = 'route_attribute_provider.route_loader';

    /**
     * @param string[] $directories
     *
     * @return string
     */
    public function createCacheKey(array $directories): string
    {
        return sprintf(
            '%s.%s',
            $this->cacheKeyPrefix,
            sha1(implode('', $directories))
        );
    }
}
