<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\RouteLoader\ClassReflector;

use Jerowork\FileClassReflector\FileFinder\RegexIterator\RegexIteratorFileFinder;
use Jerowork\FileClassReflector\PhpDocumentor\PhpDocumentorClassReflectorFactory;
use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\RouteLoader\ClassReflector\ClassReflectorRouteLoader;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass1;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use phpDocumentor\Reflection\Php\ProjectFactory;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ClassReflectorRouteLoaderTest extends TestCase
{
    public function testItShouldGetLoadedRoutes(): void
    {
        $loader = new ClassReflectorRouteLoader(new PhpDocumentorClassReflectorFactory(
            ProjectFactory::createInstance(),
            new RegexIteratorFileFinder()
        ));
        $loader->addDirectory(__DIR__ . '/../../resources');

        $loadedRoutes = iterator_to_array($loader->getRoutes());

        $this->assertCount(6, $loadedRoutes);

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[0];

        $this->assertSame(StubClass::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/minimalist', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::GET], $loadedRoute->getRoute()->getMethods());
        $this->assertNull($loadedRoute->getRoute()->getName());
        $this->assertSame([], $loadedRoute->getRoute()->getMiddleware());
        
        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[1];

        $this->assertSame(StubClass1::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/retest', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::GET], $loadedRoute->getRoute()->getMethods());
        $this->assertNull($loadedRoute->getRoute()->getName());
        $this->assertSame([], $loadedRoute->getRoute()->getMiddleware());

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[2];

        $this->assertSame(StubClass2::class, $loadedRoute->getClassName());
        $this->assertSame('handle', $loadedRoute->getMethodName());
        $this->assertSame('/extended', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::POST], $loadedRoute->getRoute()->getMethods());
        $this->assertSame('extended.route', $loadedRoute->getRoute()->getName());
        $this->assertSame([StubClass4::class], $loadedRoute->getRoute()->getMiddleware());

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[3];

        $this->assertSame(StubClass3::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/full-fledged', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::POST, RequestMethod::PUT], $loadedRoute->getRoute()->getMethods());
        $this->assertSame('full-fledged.route', $loadedRoute->getRoute()->getName());
        $this->assertSame([StubClass4::class, stdClass::class], $loadedRoute->getRoute()->getMiddleware());

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[4];

        $this->assertSame(StubClass3::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/alternative-second', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::DELETE, RequestMethod::OPTIONS], $loadedRoute->getRoute()->getMethods());
        $this->assertSame('alternative-second.route', $loadedRoute->getRoute()->getName());
        $this->assertSame([], $loadedRoute->getRoute()->getMiddleware());

        /** @var LoadedRoute $loadedRoute */
        $loadedRoute = $loadedRoutes[5];

        $this->assertSame(StubClass4::class, $loadedRoute->getClassName());
        $this->assertSame('__invoke', $loadedRoute->getMethodName());
        $this->assertSame('/class4', $loadedRoute->getRoute()->getPattern());
        $this->assertSame([RequestMethod::GET], $loadedRoute->getRoute()->getMethods());
        $this->assertNull($loadedRoute->getRoute()->getName());
        $this->assertSame([], $loadedRoute->getRoute()->getMiddleware());
    }
}
