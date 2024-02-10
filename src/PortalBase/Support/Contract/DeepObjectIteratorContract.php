<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

class DeepObjectIteratorContract
{
    /**
     * @var array<class-string, \ReflectionProperty[]>
     */
    private array $reflectionProperties = [];

    /**
     * @param object|iterable $object
     *
     * @return iterable|object[]
     * @psalm-return iterable<array-key, object>
     */
    public function iterate($object): iterable
    {
        $toIterate = [$object];
        $alreadyChecked = [];

        do {
            $finished = true;
            $newIterables = new \AppendIterator();

            /** @var mixed $iterable */
            foreach ($toIterate as $iterable) {
                if (\is_iterable($iterable)) {
                    $newIterables->append(
                        new \IteratorIterator(
                            \iterable_to_traversable($this->iterateIterable($iterable))
                        )
                    );

                    $finished = false;
                } elseif (\is_object($iterable)) {
                    $class = \get_class($iterable);

                    if (\in_array($iterable, $alreadyChecked[$class] ?? [], true)) {
                        continue;
                    }

                    $alreadyChecked[$class][] = $iterable;
                    yield $iterable;

                    $newIterables->append(
                        new \IteratorIterator(
                            \iterable_to_traversable($this->iterateProperties($iterable))
                        )
                    );

                    $finished = false;
                }
            }

            $toIterate = $newIterables;
        } while (!$finished);
    }

    private function iterateProperties(object $object): iterable
    {
        try {
            foreach ($this->getPropertiesAccessor($object) as $prop) {
                try {
                    /** @var mixed $value */
                    $value = $prop->getValue($object);

                    if (\is_object($value) || \is_iterable($value) || (\is_array($value) && $value !== [])) {
                        yield $value;
                    }
                } catch (\Throwable $_) {
                }
            }
        } catch (\Throwable $_) {
        }
    }

    private function iterateIterable(iterable $iterable): iterable
    {
        yield from $iterable;
    }

    /**
     * @throws \ReflectionException
     *
     * @return \ReflectionProperty[]
     */
    private function getPropertiesAccessor(object $object): array
    {
        $class = \get_class($object);
        $result = $this->reflectionProperties[$class] ?? null;

        if (\is_array($result)) {
            return $result;
        }

        $preResult = new \ReflectionClass($class);
        $result = [];

        foreach ($preResult->getProperties() as $property) {
            $property->setAccessible(true);
            $result[] = $property;
        }

        return $this->reflectionProperties[$class] = $result;
    }
}
