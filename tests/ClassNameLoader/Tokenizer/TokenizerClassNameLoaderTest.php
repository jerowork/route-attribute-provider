<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\ClassNameLoader\Tokenizer;

use Jerowork\RouteAttributeProvider\ClassNameLoader\Tokenizer\TokenizerClassNameLoader;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use PHPUnit\Framework\TestCase;

final class TokenizerClassNameLoaderTest extends TestCase
{
    public function testItShouldGetClassNames(): void
    {
        $loader     = new TokenizerClassNameLoader();
        $classNames = $loader->load(__DIR__ . '/../../resources');

        $this->assertSame(
            [
                StubClass::class,
                StubClass2::class,
                StubClass3::class,
                StubClass4::class,
            ],
            iterator_to_array($classNames)
        );
    }
}
