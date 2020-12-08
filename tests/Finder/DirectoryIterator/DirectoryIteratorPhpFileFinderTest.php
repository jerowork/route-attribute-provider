<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Test\Finder\DirectoryIterator;

use Jerowork\RouteAttributeProvider\Finder\DirectoryIterator\DirectoryIteratorPhpFileFinder;
use Jerowork\RouteAttributeProvider\Test\resources\directory\StubClass3;
use Jerowork\RouteAttributeProvider\Test\resources\directory\sub\StubClass4;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass;
use Jerowork\RouteAttributeProvider\Test\resources\StubClass2;
use PHPUnit\Framework\TestCase;

final class DirectoryIteratorPhpFileFinderTest extends TestCase
{
    public function testItShouldFindPhpFiles(): void
    {
        $finder = new DirectoryIteratorPhpFileFinder();

        $files = $finder->find(__DIR__ . '/../../resources');

        $this->assertSame(
            [
                __DIR__ . '/../../resources/StubClass.php',
                __DIR__ . '/../../resources/StubClass2.php',
                __DIR__ . '/../../resources/directory/StubClass3.php',
                __DIR__ . '/../../resources/directory/sub/StubClass4.php',
            ],
            iterator_to_array($files)
        );
    }
}
