<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test;

use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteAttributeConfigurator;
use Jerowork\RouteAttributeProvider\RouteAttributeProviderInterface;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

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
                StubClass2::class,
                'handle',
                Mockery::on(function (Route $route): bool {
                    $this->assertSame('/extended', $route->getPattern());

                    return true;
                })
            );

        $configurator->addDirectory(__DIR__.'/resources')->configure();
    }
}
