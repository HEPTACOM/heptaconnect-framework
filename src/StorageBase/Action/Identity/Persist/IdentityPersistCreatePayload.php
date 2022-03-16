<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;

final class IdentityPersistCreatePayload extends IdentityPersistPayloadContract
{
    private string $externalId;

    public function __construct(MappingNodeKeyInterface $mappingNodeKey, string $externalId)
    {
        parent::__construct($mappingNodeKey);
        $this->externalId = $externalId;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }
}
