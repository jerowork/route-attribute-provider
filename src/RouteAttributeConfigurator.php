<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider;

use Jerowork\FileClassReflector\FileFinder\RegexIterator\RegexIteratorFileFinder;
use Jerowork\FileClassReflector\NikicParser\NikicParserClassReflectorFactory;
use Jerowork\RouteAttributeProvider\RouteLoader\Cache\CacheRouteLoaderDecorator;
use Jerowork\RouteAttributeProvider\RouteLoader\ClassReflector\ClassReflectorRouteLoader;
use Jerowork\RouteAttributeProvider\RouteLoader\RouteLoaderInterface;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Psr\SimpleCache\CacheInterface;

final class RouteAttributeConfigurator
{
    private RouteLoaderInterface $routeLoader;

    /**
     * @var string[] $directories
     */
    private array $directories = [];

    public function __construct(
        private RouteAttributeProviderInterface $routeAttributeProvider,
        ?RouteLoaderInterface $routeLoader = null
    ) {
        $this->routeLoader = $routeLoader ?? new ClassReflectorRouteLoader(
            new NikicParserClassReflectorFactory(
                new RegexIteratorFileFinder(),
                (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
                new NodeTraverser()
            )
        );
    }

    public function addDirectory(string ...$directories) : self
    {
        foreach ($directories as $directory) {
            $this->directories[] = $directory;
        }

        return $this;
    }

    public function enableCache(CacheInterface $cache) : self
    {
        $this->routeLoader = new CacheRouteLoaderDecorator($this->routeLoader, $cache);

        return $this;
    }

    public function configure() : void
    {
        foreach ($this->routeLoader->addDirectory(...$this->directories)->getRoutes() as $loadedRoute) {
            $this->routeAttributeProvider->configure(
                $loadedRoute->getClassName(),
                $loadedRoute->getMethodName(),
                $loadedRoute->getRoute()
            );
        }
    }

    public function getRouteLoader() : RouteLoaderInterface
    {
        return $this->routeLoader;
    }
}
