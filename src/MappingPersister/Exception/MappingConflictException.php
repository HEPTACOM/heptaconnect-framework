<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\MappingPersister\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class MappingConflictException extends \Exception
{
    private PortalNodeKeyInterface $portalNodeKey;

    private MappingNodeKeyInterface $mappingNodeKey;

    private string $externalId;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        MappingNodeKeyInterface $mappingNodeKey,
        string $externalId
    ) {
        $message = 'CONFLICT '.$externalId;

        parent::__construct($message);
        $this->portalNodeKey = $portalNodeKey;
        $this->mappingNodeKey = $mappingNodeKey;
        $this->externalId = $externalId;
    }
}
