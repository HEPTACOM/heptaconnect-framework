<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\MappingCollection;

interface EmitterInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
     */
    public function emit(MappingCollection $mappings, EmitContextInterface $context, EmitterStackInterface $stack): iterable;

    /**
     * @return array<array-key, class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>>
     */
    public function supports(): array;
}
