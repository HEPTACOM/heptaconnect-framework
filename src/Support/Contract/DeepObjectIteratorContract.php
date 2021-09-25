<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

class DeepObjectIteratorContract
{
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
            $newIterables = [];

            /** @var mixed $iterable */
            foreach ($toIterate as $iterable) {
                if (\is_object($iterable)) {
                    $class = \get_class($iterable);

                    if (\in_array($iterable, $alreadyChecked[$class] ?? [], true)) {
                        continue;
                    }

                    $alreadyChecked[$class][] = $iterable;
                    yield $iterable;
                    $newIterables[] = \iterable_to_array($this->iterateProperties($iterable));
                } elseif (\is_iterable($iterable)) {
                    $newIterables[] = \iterable_to_array($this->iterateIterable($iterable));
                }
            }

            $toIterate = \array_merge([], ...$newIterables);
        } while ($toIterate !== []);
    }

    private function iterateProperties(object $object): iterable
    {
        try {
            foreach ((new \ReflectionClass($object))->getProperties() as $prop) {
                $prop->setAccessible(true);

                try {
                    yield $prop->getValue($object);
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
}
