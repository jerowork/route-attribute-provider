<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Api;

use Attribute;
use JsonSerializable;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Route implements JsonSerializable
{
    private string $pattern;

    /**
     * @var string[]
     */
    private array $methods;

    private ?string $name;

    /**
     * Set of middleware FQCNs.
     *
     * @var string[]
     */
    private array $middleware;

    /**
     * @param string|string[] $method
     * @param string|string[] $middleware
     */
    public function __construct(
        string $pattern,
        string | array $method = [RequestMethod::GET],
        string $name = null,
        string | array $middleware = []
    ) {
        $this->pattern    = $pattern;
        $this->methods    = is_string($method) === true ? [$method] : $method;
        $this->name       = $name;
        $this->middleware = is_string($middleware) === true ? [$middleware] : $middleware;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'pattern'    => $this->getPattern(),
            'methods'    => $this->methods,
            'name'       => $this->name,
            'middleware' => $this->middleware,
        ];
    }
}
