<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\MappingPersister\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class MappingConflictException extends \Exception
{
    public const FORMAT = 'Conflicting mapping; PortalNode: %s; MappingNode: %s; PrimaryKey: %s';

    private PortalNodeKeyInterface $portalNodeKey;

    private MappingNodeKeyInterface $mappingNodeKey;

    private string $externalId;

    public function __construct(
        string $message,
        PortalNodeKeyInterface $portalNodeKey,
        MappingNodeKeyInterface $mappingNodeKey,
        string $externalId
    ) {
        parent::__construct($message);
        $this->portalNodeKey = $portalNodeKey;
        $this->mappingNodeKey = $mappingNodeKey;
        $this->externalId = $externalId;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getMappingNodeKey(): MappingNodeKeyInterface
    {
        return $this->mappingNodeKey;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
