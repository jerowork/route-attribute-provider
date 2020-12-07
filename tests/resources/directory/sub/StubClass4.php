<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\resources\directory\sub;

use Jerowork\RouteAttributeProvider\Api\Route;

final class StubClass4
{
    #[Route('/class4')]
    public function __invoke(): void
    {}
}
