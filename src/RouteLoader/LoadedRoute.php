<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader;

use Jerowork\RouteAttributeProvider\Api\Route;
use JsonSerializable;

final class LoadedRoute implements JsonSerializable
{
    // phpcs:disable
    public function __construct(
        private string $className,
        private string $methodName,
        private Route $route
    ) {
    }

    // phpcs:enable

    /**
     * @param array<string,mixed> $payload
     */
    public static function fromPayload(array $payload) : self
    {
        return new self($payload['className'], $payload['methodName'], Route::fromPayload($payload['route']));
    }

    public function getClassName() : string
    {
        return $this->className;
    }

    public function getMethodName() : string
    {
        return $this->methodName;
    }

    public function getRoute() : Route
    {
        return $this->route;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize() : array
    {
        return [
            'className'  => $this->className,
            'methodName' => $this->methodName,
            'route'      => $this->route->jsonSerialize(),
        ];
    }
}
