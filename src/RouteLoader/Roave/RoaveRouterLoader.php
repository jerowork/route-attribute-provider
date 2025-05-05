<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Roave;

use Generator;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteLoader\LoadedRoute;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;
use ReflectionClass;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\Reflector\Reflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

final class RoaveRouterLoader implements RouteLoaderInterface
{
    /**
     * @var string[]
     */
    private array $directories = [];

    public function __construct(
        private readonly FileFinder $fileFinder,
    ) {
    }

    public function getDirectories() : array
    {
        return $this->directories;
    }

    public function addDirectory(string ...$directories) : RouteLoaderInterface
    {
        $this->directories = [...$this->directories, ...$directories];

        return $this;
    }

    public function getRoutes() : Generator
    {
        foreach ($this->fileFinder->getFiles(...$this->directories) as $file) {
            $reflector = $this->createRoaveReflectorForFile($file);

            foreach ($reflector->reflectAllClasses() as $reflectorClass) {
                $hasClassAttribute = false;
                $class             = new ReflectionClass($reflectorClass->getName());

                foreach ($class->getAttributes(Route::class) as $attribute) {
                    $hasClassAttribute = true;

                    /** @var Route $route */
                    $route = $attribute->newInstance();

                    yield new LoadedRoute($class->getName(), '__invoke', $route);
                }

                if (!$hasClassAttribute) {
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
    }

    public function createRoaveReflectorForFile(string $file) : Reflector
    {
        return new DefaultReflector(new SingleFileSourceLocator($file, (new BetterReflection())->astLocator()));
    }
}
