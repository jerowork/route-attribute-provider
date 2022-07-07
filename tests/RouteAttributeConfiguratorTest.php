<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test;

use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteAttributeConfigurator;
use Jerowork\RouteAttributeProvider\RouteAttributeProviderInterface;
use Jerowork\RouteAttributeProvider\RouteLoader\Cache\CacheRouteLoaderDecorator;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass1;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\SimpleCache\CacheInterface;

final class RouteAttributeConfiguratorTest extends MockeryTestCase
{
    public function testItShouldConfigure(): void
    {
        $configurator = new RouteAttributeConfigurator(
            $provider = Mockery::mock(RouteAttributeProviderInterface::class)
        );

        $provider->expects('configure')
            ->once()
            ->with(
                StubClass4::class,
                '__invoke',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/class4', $route->getPattern());

                    return true;
                })
            );

        $provider->expects('configure')
            ->once()
            ->with(
                StubClass3::class,
                '__invoke',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/full-fledged', $route->getPattern());

                    return true;
                })
            );

        $provider->expects('configure')
            ->once()
            ->with(
                StubClass3::class,
                '__invoke',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/alternative-second', $route->getPattern());

                    return true;
                })
            );

        $provider->expects('configure')
            ->once()
            ->with(
                StubClass::class,
                '__invoke',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/minimalist', $route->getPattern());

                    return true;
                })
            );
            
        $provider->expects('configure')
            ->once()
            ->with(
                StubClass1::class,
                '__invoke',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/retest', $route->getPattern());

                    return true;
                })
            );

        $provider->expects('configure')
            ->once()
            ->with(
                StubClass2::class,
                'handle',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/extended', $route->getPattern());

                    return true;
                })
            );

        $configurator->addDirectory(__DIR__ . '/resources')->configure();
    }

    public function testItShouldHaveDisabledCacheByDefault(): void
    {
        $configurator = new RouteAttributeConfigurator(
            Mockery::mock(RouteAttributeProviderInterface::class)
        );

        $this->assertNotInstanceOf(CacheRouteLoaderDecorator::class, $configurator->getRouteLoader());
    }

    public function testItShouldEnableCache(): void
    {
        $configurator = new RouteAttributeConfigurator(
            Mockery::mock(RouteAttributeProviderInterface::class)
        );

        $configurator->enableCache(Mockery::mock(CacheInterface::class));

        $this->assertInstanceOf(CacheRouteLoaderDecorator::class, $configurator->getRouteLoader());
    }
}
