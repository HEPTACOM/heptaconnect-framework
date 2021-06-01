<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

interface EmitterStackInterface
{
    /**
     * @param string[] $externalIds
     *
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function next(iterable $externalIds, EmitContextInterface $context): iterable;

    public function supports(): string;
}
