<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\resources\directory;

use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use stdClass;

final class StubClass3
{
    #[Route(
        '/full-fledged',
        method: [RequestMethod::POST, RequestMethod::PUT],
        name: 'full-fledged.route',
        middleware: [StubClass4::class, stdClass::class]
    )]
    #[Route(
        '/alternative-second',
        method: [RequestMethod::DELETE, RequestMethod::OPTIONS],
        name: 'alternative-second.route'
    )]
    public function __invoke(): void
    {
    }
}
