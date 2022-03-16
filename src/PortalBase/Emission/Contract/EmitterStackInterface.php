<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

/**
 * Resembles a collection of emitters that can be used to emit a common supported entity type.
 */
interface EmitterStackInterface
{
    /**
     * Forwards the emission to the next emitter on the stack and returns its response.
     *
     * @param string[] $externalIds
     *
     * @return iterable<array-key, DatasetEntityContract>
     */
    public function next(iterable $externalIds, EmitContextInterface $context): iterable;

    /**
     * Gets the supported entity type of the stack.
     *
     * @return class-string<DatasetEntityContract>
     */
    public function supports(): string;
}
