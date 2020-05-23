<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Contract\StorageMappingNodeKeyInterface;

interface MappingNodeStructInterface
{
    public function getKey(): StorageMappingNodeKeyInterface;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getDatasetEntityClassName(): string;
}
