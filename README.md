# route-attribute-provider
[![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fjerowork%2Froute-attribute-provider%2Fbadge%3Fref%3Dmain&style=flat-square)](https://github.com/jerowork/route-attribute-provider/actions)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jerowork/route-attribute-provider.svg?style=flat-square)](https://scrutinizer-ci.com/g/jerowork/route-attribute-provider/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jerowork/route-attribute-provider.svg?style=flat-square)](https://scrutinizer-ci.com/g/jerowork/route-attribute-provider)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/jerowork/route-attribute-provider.svg?style=flat-square&include_prereleases)](https://packagist.org/packages/jerowork/route-attribute-provider)
[![PHP Version](https://img.shields.io/badge/php-%5E8.0-8892BF.svg?style=flat-square)](http://www.php.net)

Define routes by PHP8 [attributes]((https://stitcher.io/blog/attributes-in-php-8)).

## Installation
Install via [Composer](https://getcomposer.org):
```bash
$ composer require jerowork/route-attribute-provider
```

## Configuration
In order to use route attributes, pick any of the existing framework implementations or create a custom one.

### Existing implementations
- [jerowork/slim-route-attribute-provider](https://github.com/jerowork/slim-route-attribute-provider) for [Slim](https://www.slimframework.com)

Or check [packagist](https://packagist.org/providers/psr/container-implementation) for any other implementations.

### Custom implementation
Create a custom implementation by using `RouteAttributeProviderInterface`.

A (fictive) custom implementation:
```php
use Jerowork\RouteAttributeProvider\RouteAttributeProviderInterface;

final class CustomRouteProvider implements RouteAttributeProviderInterface
{
    private SomeRouter $router;
    
    public function __construct(SomeRouter $router)
    {
        $this->router = $router;
    }

    public function configure(string $className,string $methodName,\Jerowork\RouteAttributeProvider\Api\Route $route) : void
    {
        // Register rule at your router
        $rule = $this->router->addRule(
            $route->getMethods(),
            $route->getPattern(),
            $className.':'.$methodName,
            $route->getName()
        );

        // Register optional middleware
        foreach ($route->getMiddleware() as $middleware) {
            $rule->addMiddleware($middleware);
        }
    }
}
```

Instantiate `RouteAttributeConfigurator` somewhere close to the construction of your application,
e.g. in your front controller (or ideally register in your PSR-11 container).

```php
use Jerowork\RouteAttributeProvider\RouteAttributeConfigurator;

// ...

$routeConfigurator = new RouteAttributeConfigurator(
    new CustomRouteProvider($router)
);

$routeConfigurator->configure(sprintf('%s/src/Infrastructure/Api/Http/Action', __DIR__));

// ...
```

## Usage
A route can be defined via PHP8 attributes.

Minimalist example:

```php
use Jerowork\RouteAttributeProvider\Api\Route;
use Psr\Http\Message\ResponseInterface as ServerRequest;
use Psr\Http\Message\ServerRequestInterface as Response;

final class RootAction
{
    #[Route('/root')]
    public function __invoke(ServerRequest $request, Response $response) : Response
    {
        return $response;
    }
}
```

Extended example:

```php
use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use Psr\Http\Message\ResponseInterface as ServerRequest;
use Psr\Http\Message\ServerRequestInterface as Response;

final class RootAction
{
    #[Route('/root', method: RequestMethod::GET, name: 'root', middleware: SomeMiddleware::class)]
    public function __invoke(ServerRequest $request, Response $response) : Response
    {
        return $response;
    }
}
```

Full-fledged example:

```php
use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use Psr\Http\Message\ResponseInterface as ServerRequest;
use Psr\Http\Message\ServerRequestInterface as Response;

final class RootAction
{
    #[Route('/root',
        method: [RequestMethod::GET, RequestMethod::POST],
        name: 'root',
        middleware: [
            SomeMiddleware::class,
            AnotherMiddleware::class,
        ]
    )]
    #[Route('/second-route',
        method: RequestMethod::DELETE,
        name: 'second-route'
    )]
    public function __invoke(ServerRequest $request, Response $response) : Response
    {
        return $response;
    }
}
```
