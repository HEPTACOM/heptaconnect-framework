<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface MappingInterface
{
    public function getExternalId(): ?string;

    public function setExternalId(string $externalId): self;

    public function getPortalNodeId(): string;

    public function getDatasetEntityClassName(): string;
}
