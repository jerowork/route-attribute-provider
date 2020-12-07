<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\resources;

use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;

final class StubClass2
{
    #[Route('/extended', method: RequestMethod::POST, name: 'extended.route', middleware: StubClass4::class)]
    public function handle(): void
    {}
}
