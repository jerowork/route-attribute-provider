<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider;

use Jerowork\RouteAttributeProvider\ClassNameLoader\ClassNameLoaderInterface;
use Jerowork\RouteAttributeProvider\ClassNameLoader\Tokenizer\TokenizerClassNameLoader;
use Jerowork\RouteAttributeProvider\RouteLoader\Reflection\ReflectionRouteLoader;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;

final class RouteAttributeConfigurator
{
    private RouteAttributeProviderInterface $routeAttributeProvider;
    private ClassNameLoaderInterface $classNameLoader;
    private RouteLoaderInterface $routeLoader;

    /**
     * @var string[] $directories
     */
    private array $directories;

    public function __construct(
        RouteAttributeProviderInterface $routeAttributeProvider,
        ?ClassNameLoaderInterface $classNameLoader = null,
        ?RouteLoaderInterface $routeLoader = null
    ) {
        $this->routeAttributeProvider = $routeAttributeProvider;
        $this->classNameLoader        = $classNameLoader ?? new TokenizerClassNameLoader();
        $this->routeLoader            = $routeLoader ?? new ReflectionRouteLoader();
        $this->directories            = [];
    }

    public function addDirectory(string ...$directories): self
    {
        foreach ($directories as $directory) {
            $this->directories[] = $directory;
        }

        return $this;
    }

    public function configure(): void
    {
        foreach ($this->directories as $directory) {
            foreach ($this->classNameLoader->load($directory) as $className) {
                $this->configureForClassName($className);
            }
        }
    }

    /**
     * @param class-string $className
     */
    private function configureForClassName(string $className): void
    {
        $loadedRoutes = $this->routeLoader->load($className);

        foreach ($loadedRoutes as $loadedRoute) {
            $this->routeAttributeProvider->configure(
                $loadedRoute->getClassName(),
                $loadedRoute->getMethodName(),
                $loadedRoute->getRoute()
            );
        }
    }
}
