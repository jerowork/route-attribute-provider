<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\resources;

use Jerowork\RouteAttributeProvider\Api\Route;

final class StubClass
{
    #[Route('/minimalist')]
    public function __invoke(): void
    {
    }
}
