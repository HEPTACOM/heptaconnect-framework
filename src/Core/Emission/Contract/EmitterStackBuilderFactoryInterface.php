<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface EmitterStackBuilderFactoryInterface
{
    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function createEmitterStackBuilder(
        PortalNodeKeyInterface $portalNodeKey,
        string $entityType
    ): EmitterStackBuilderInterface;
}
