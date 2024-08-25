<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Php;

use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;

final class ClassCodeHasher
{
    private static array $fileHashes = [];

    private function __construct()
    {
    }

    static function hashClassStringCode(ClassStringReferenceContract $classString): string
    {
        return self::hashReflectionClassCode(new \ReflectionClass((string) $classString));
    }

    static function hashClassCode(object $class): string
    {
        return self::hashReflectionClassCode(new \ReflectionClass($class));
    }

    static function hashReflectionClassCode(\ReflectionClass $reflectionClass): string
    {
        if (isset(self::$fileHashes[$reflectionClass->name])) {
            return self::$fileHashes[$reflectionClass->name];
        }

        $results = [];
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass !== false) {
            $results[] = self::hashReflectionClassCode($parentClass);
        }

        foreach ($reflectionClass->getInterfaces() as $interface) {
            $results[] = self::hashReflectionClassCode($interface);
        }

        foreach ($reflectionClass->getTraits() as $trait) {
            $results[] = self::hashReflectionClassCode($trait);
        }

        $fileName = $reflectionClass->getFileName();
        $classHash = $reflectionClass->name;

        if ($fileName !== false) {
            $content = \file_get_contents($fileName);

            if ($content !== false) {
                $start = $reflectionClass->getStartLine();
                $end = $reflectionClass->getEndLine();

                if ($start !== false && $end !== false) {
                    $lines = \explode("\n", $content);
                    $lines = \array_slice($lines, $start, ($end - $start) + 1);
                    $content = \implode("\n", $lines);
                    unset($lines);
                }

                $classHash = \hash('xxh128', $content);
            }
        }

        $results[] = $classHash;
        $result = \implode('-', $results);
        self::$fileHashes[$reflectionClass->name] = $result;

        return $result;
    }
}
