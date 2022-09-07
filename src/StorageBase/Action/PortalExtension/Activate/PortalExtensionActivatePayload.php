<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalExtensionActivatePayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private PortalExtensionTypeCollection $extensions;

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->extensions = new PortalExtensionTypeCollection();
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function addExtension(PortalExtensionType $portalExtensionType): void
    {
        if ($this->extensions->contains($portalExtensionType)) {
            return;
        }

        $this->extensions->push([$portalExtensionType]);
    }

    public function removeExtension(PortalExtensionType $portalExtensionType): void
    {
        $this->extensions = $this->extensions->filter(
            static fn (PortalExtensionType $item): bool => !$item->equals($portalExtensionType)
        );
    }

    public function getExtensions(): PortalExtensionTypeCollection
    {
        return new PortalExtensionTypeCollection($this->extensions);
    }
}
