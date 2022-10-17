<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class IdentityConflictException extends \Exception
{
    public const FORMAT = 'Conflicting identity; PortalNode: %s; MappingNode: %s; PrimaryKey: %s';

    public function __construct(
        string $message,
        int $code,
        private PortalNodeKeyInterface $portalNodeKey,
        private MappingNodeKeyInterface $mappingNodeKey,
        private string $externalId
    ) {
        parent::__construct($message, $code);
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
