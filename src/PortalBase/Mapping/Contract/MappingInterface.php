<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface MappingInterface
{
    public function getExternalId(): ?string;

    public function setExternalId(?string $externalId): self;

    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getMappingNodeKey(): MappingNodeKeyInterface;

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getEntityType(): string;
}
