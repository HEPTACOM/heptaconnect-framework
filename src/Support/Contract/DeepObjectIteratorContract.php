<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

class DeepObjectIteratorContract
{
    /**
     * @return iterable|object[]
     * @psalm-return iterable<array-key, object>
     */
    public function iterate($datasetEntity): iterable
    {
        $toIterate = [$datasetEntity];
        $alreadyChecked = [];

        do {
            $newIterables = [];

            foreach ($toIterate as $iterable) {
                if (\is_object($iterable)) {
                    $objectHash = \spl_object_hash($iterable);

                    if (\in_array($objectHash, $alreadyChecked)) {
                        continue;
                    }

                    $alreadyChecked[] = $objectHash;
                    yield $iterable;
                }

                if (\is_callable($strategy = $this->getStrategy($iterable))) {
                    $newIterables[] = \iterator_to_array($strategy($iterable));
                }
            }

            $toIterate = \array_merge([], ...$newIterables);
        } while ($toIterate !== []);
    }

    private function getStrategy($any): ?callable
    {
        if (\is_object($any)) {
            return [$this, 'iterateProperties'];
        }

        if (\is_iterable($any)) {
            return [$this, 'iterateIterable'];
        }

        return null;
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
