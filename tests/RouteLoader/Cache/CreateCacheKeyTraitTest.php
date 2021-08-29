<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader\Cache;

use Jerowork\RouteAttributeProvider\RouteLoader\Cache\CreateCacheKeyTrait;
use PHPUnit\Framework\TestCase;

final class CreateCacheKeyTraitTest extends TestCase
{
    public function testItShouldCreateCacheKey(): void
    {
        $trait = new class () {
            use CreateCacheKeyTrait;
        };

        $cacheKey = $trait->createCacheKey(['/path/to/a', '/path/to/b']);

        $this->assertSame(
            'route_attribute_provider.route_loader.85749fda2f9a85eadddfadf5dda900d2423a34d0',
            $cacheKey
        );
    }
}
