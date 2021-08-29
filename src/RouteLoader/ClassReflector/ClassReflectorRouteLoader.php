<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\ClassReflector;

use Generator;
use Jerowork\FileClassReflector\ClassReflectorFactory;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;

final class ClassReflectorRouteLoader implements RouteLoaderInterface
{
    /**
     * @var string[]
     */
    private array $directories = [];

    public function __construct(private ClassReflectorFactory $reflectorFactory)
    {
    }

    public function getDirectories() : array
    {
        return $this->directories;
    }

    public function addDirectory(string ...$directories) : RouteLoaderInterface
    {
        /** @psalm-suppress DuplicateArrayKey */
        $this->directories = [...$this->directories, ...$directories];

        return $this;
    }

    public function getRoutes() : Generator
    {
        $reflector = $this->reflectorFactory->create()->addDirectory(...$this->directories);

        foreach ($reflector->reflect()->getClasses() as $class) {
            foreach ($class->getMethods() as $method) {
                foreach ($method->getAttributes(Route::class) as $attribute) {
                    /** @var Route $route */
                    $route = $attribute->newInstance();

                    yield new LoadedRoute($class->getName(), $method->getName(), $route);
                }
            }
        }
    }
}
