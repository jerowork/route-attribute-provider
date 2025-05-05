<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\RouteLoader\Roave\RegexIterator;

use Jerowork\RouteAttributeProvider\RouteLoader\Roave\FileFinder;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

final class RegexIteratorFileFinder implements FileFinder
{
    private const REGEX_PHP_FILE = '/^.+\.php$/i';

    public function getFiles(string ...$directories) : array
    {
        $files = [];

        foreach ($directories as $directory) {
            $filesIterator = new RegexIterator(
                new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)),
                self::REGEX_PHP_FILE,
                RegexIterator::GET_MATCH,
            );

            /** @var array<int, string> $filePath */
            foreach ($filesIterator as $filePath) {
                if (isset($filePath[0]) === false) {
                    continue;
                }

                $files[] = $filePath[0];
            }
        }

        sort($files);

        return $files;
    }
}
