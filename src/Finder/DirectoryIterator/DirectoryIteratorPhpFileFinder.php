<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Finder\DirectoryIterator;

use Generator;
use Jerowork\RouteAttributeProvider\Finder\PhpFileFinderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

final class DirectoryIteratorPhpFileFinder implements PhpFileFinderInterface
{
    private const REGEX_PHP_FILES = '/^.+\.php$/i';

    public function find(string $directory): Generator
    {
        $filesIterator = new RegexIterator(
            new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)),
            self::REGEX_PHP_FILES,
            RecursiveRegexIterator::GET_MATCH
        );

        foreach ($filesIterator as $filePath) {
            $filePath = $filePath[0] ?? null;

            if ($filePath === null) {
                continue;
            }

            yield $filePath;
        }
    }
}
