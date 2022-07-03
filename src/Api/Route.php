<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Api;

use Attribute;
use JsonSerializable;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
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

    private ?string $host;

    /**
     * @var string[]
     */
    private array $schemes;

    private ?int $httpPort;
    private ?int $httpsPort;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * @param string|string[]      $method
     * @param string|string[]      $middleware
     * @param string|string[]      $schemes
     * @param array<string, mixed> $options
     */
    public function __construct(
        string $pattern,
        string|array $method = [RequestMethod::GET],
        string $name = null,
        string|array $middleware = [],
        string $host = null,
        string|array $schemes = [],
        int $httpPort = null,
        int $httpsPort = null,
        array $options = []
    ) {
        $this->pattern    = $pattern;
        $this->methods    = is_string($method) === true ? [$method] : $method;
        $this->name       = $name;
        $this->middleware = is_string($middleware) === true ? [$middleware] : $middleware;
        $this->host       = $host;
        $this->schemes    = is_string($schemes) === true ? [$schemes] : $schemes;
        $this->httpPort   = $httpPort;
        $this->httpsPort  = $httpsPort;
        $this->options    = $options;
    }

    /**
     * @param array<string,mixed> $payload
     */
    public static function fromPayload(array $payload) : self
    {
        return new self(
            $payload['pattern'],
            $payload['methods'],
            $payload['name'],
            $payload['middleware'],
            $payload['host'],
            $payload['schemes'],
            $payload['httpPort'],
            $payload['httpsPort'],
            $payload['options']
        );
    }

    public function getPattern() : string
    {
        return $this->pattern;
    }

    /**
     * @return string[]
     */
    public function getMethods() : array
    {
        return $this->methods;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getMiddleware() : array
    {
        return $this->middleware;
    }

    public function getHost() : ?string
    {
        return $this->host;
    }

    /**
     * @return string[]
     */
    public function getSchemes() : array
    {
        return $this->schemes;
    }

    public function getHttpPort() : ?int
    {
        return $this->httpPort;
    }

    public function getHttpsPort() : ?int
    {
        return $this->httpsPort;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize() : array
    {
        return [
            'pattern'    => $this->getPattern(),
            'methods'    => $this->methods,
            'name'       => $this->name,
            'middleware' => $this->middleware,
            'host'       => $this->host,
            'schemes'    => $this->schemes,
            'httpPort'   => $this->httpPort,
            'httpsPort'  => $this->httpsPort,
            'options'    => $this->options,
        ];
    }
}
