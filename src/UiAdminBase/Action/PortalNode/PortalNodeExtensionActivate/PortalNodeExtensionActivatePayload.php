<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeExtensionActivatePayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var class-string[]
     */
    private array $portalExtensionQuery = [];

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    /**
     * @return class-string[]
     */
    public function getPortalExtensionQuery(): array
    {
        return $this->portalExtensionQuery;
    }

    /**
     * @param class-string[] $portalExtensionQuery
     */
    public function setPortalExtensionQuery(array $portalExtensionQuery): void
    {
        $this->portalExtensionQuery = $portalExtensionQuery;
    }
}
