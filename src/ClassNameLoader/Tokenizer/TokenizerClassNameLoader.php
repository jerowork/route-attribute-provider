<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\ClassNameLoader\Tokenizer;

use Generator;
use Jerowork\RouteAttributeProvider\Finder\DirectoryIterator\DirectoryIteratorPhpFileFinder;
use Jerowork\RouteAttributeProvider\Finder\PhpFileFinderInterface;
use Jerowork\RouteAttributeProvider\ClassNameLoader\ClassNameLoaderInterface;
use PhpToken;

final class TokenizerClassNameLoader implements ClassNameLoaderInterface
{
    private PhpFileFinderInterface $phpFileFinder;

    public function __construct(?PhpFileFinderInterface $phpFileFinder = null)
    {
        $this->phpFileFinder = $phpFileFinder ?? new DirectoryIteratorPhpFileFinder();
    }

    public function load(string $directory): Generator
    {
        foreach ($this->phpFileFinder->find($directory) as $filePath) {
            $className = $this->loadFromFilePath($filePath);

            if ($className === null) {
                continue;
            }

            /** @var class-string $className */
            yield $className;
        }
    }

    private function loadFromFilePath(string $filePath): ?string
    {
        $tokens     = PhpToken::tokenize((string) file_get_contents($filePath));
        $tokenCount = count($tokens);

        $className = [];
        for ($i = 0; $i < $tokenCount; $i++) {
            // namespace (only once, no use imports)
            if ($tokens[$i]->id === T_NAME_QUALIFIED && count($className) === 0) {
                $className[] = $tokens[$i]->text;
            }

            // class name
            if (
                isset($tokens[$i - 2]) === true &&
                isset($tokens[$i - 1]) === true &&
                $tokens[$i - 2]->id === T_CLASS &&
                $tokens[$i - 1]->id === T_WHITESPACE &&
                $tokens[$i]->id === T_STRING
            ) {
                $className[] = $tokens[$i]->text;

                return implode('\\', $className);
            }
        }

        return null;
    }
}
