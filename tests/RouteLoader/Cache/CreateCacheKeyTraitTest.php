<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader\Cache;

use Jerowork\RouteAttributeProvider\RouteLoader\Cache\CreateCacheKeyTrait;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use PHPUnit\Framework\TestCase;

final class CreateCacheKeyTraitTest extends TestCase
{
    public function testItShouldCreateCacheKey(): void
    {
        $trait = new class() {
            use CreateCacheKeyTrait;
        };

        $cacheKey = $trait->createCacheKey(StubClass::class);

        $this->assertSame(
            'route_attribute_provider.route_loader.Jerowork.RouteAttributeProvider.Test.resources.StubClass',
            $cacheKey
        );
    }
}
