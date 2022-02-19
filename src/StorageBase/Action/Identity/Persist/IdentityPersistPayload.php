<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class IdentityPersistPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private IdentityPersistPayloadCollection $identityPersistPayloads;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        IdentityPersistPayloadCollection $identityPersistPayloads
    ) {
        $this->portalNodeKey = $portalNodeKey;
        $this->identityPersistPayloads = $identityPersistPayloads;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getIdentityPersistPayloads(): IdentityPersistPayloadCollection
    {
        return $this->identityPersistPayloads;
    }

    public function setIdentityPersistPayloads(IdentityPersistPayloadCollection $identityPersistPayloads): void
    {
        $this->identityPersistPayloads = $identityPersistPayloads;
    }
}
