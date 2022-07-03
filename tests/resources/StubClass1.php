<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\resources;

use Jerowork\RouteAttributeProvider\Api\Route;

#[Route('/retest')]
final class StubClass1
{
    public function __invoke(): void
    {
    }
}
