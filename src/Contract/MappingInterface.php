<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface MappingInterface
{
    public function getExternalId(): ?string;

    public function setExternalId(string $externalId): self;

    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getMappingNodeKey(): MappingNodeKeyInterface;

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getDatasetEntityClassName(): string;
}
