<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\MappingCollection;

interface EmitterStackInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct>
     */
    public function next(MappingCollection $mappings, EmitContextInterface $context): iterable;
}
