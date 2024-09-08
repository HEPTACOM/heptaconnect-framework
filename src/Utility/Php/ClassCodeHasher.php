<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Php;

use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;

/**
 * Calculate source code hashes to build and detect source code changes.
 */
class ClassCodeHasher
{
    private array $fileHashes = [];

    /**
     * Returns a singleton instance for a global cache.
     */
    public static function getInstance(): ClassCodeHasher
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function hashClassStringCode(ClassStringReferenceContract $classString): string
    {
        return $this->hashReflectionClassCode(new \ReflectionClass((string) $classString));
    }

    public function hashClassCode(object $class): string
    {
        return $this-hashReflectionClassCode(new \ReflectionClass($class));
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     */
    public function hashReflectionClassCode(\ReflectionClass $reflectionClass): string
    {
        if (isset($this->fileHashes[$reflectionClass->name])) {
            return $this->fileHashes[$reflectionClass->name];
        }

        $results = [];
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass !== false) {
            $results[] = $this->hashReflectionClassCode($parentClass);
        }

        foreach ($reflectionClass->getInterfaces() as $interface) {
            $results[] = $this->hashReflectionClassCode($interface);
        }

        foreach ($reflectionClass->getTraits() as $trait) {
            $results[] = $this->hashReflectionClassCode($trait);
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
        $this->fileHashes[$reflectionClass->name] = $result;

        return $result;
    }
}
