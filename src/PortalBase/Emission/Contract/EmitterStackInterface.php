<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

interface EmitterStackInterface
{
    /**
     * @param string[] $externalIds
     *
     * @return iterable<array-key, DatasetEntityContract>
     */
    public function next(iterable $externalIds, EmitContextInterface $context): iterable;

    /**
     * @return class-string<DatasetEntityContract>
     */
    public function supports(): string;
}
