<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * Describes payloads that change portal extension types active state for a portal node.
 */
abstract class PortalExtensionActiveChangePayloadContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalExtensionTypeCollection $extensions;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey
    ) {
        $this->attachments = new AttachmentCollection();
        $this->extensions = new PortalExtensionTypeCollection();
    }

    /**
     * Get the portal node key to which the portal extension state change will be applied to.
     */
    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * Adds the portal extension to the extensions, that shall change its state.
     */
    public function addExtension(PortalExtensionType $portalExtensionType): void
    {
        if ($this->extensions->contains($portalExtensionType)) {
            return;
        }

        $this->extensions->push([$portalExtensionType]);
    }

    /**
     * Removes the portal extension to the extensions, so it doesn't change its state.
     */
    public function removeExtension(PortalExtensionType $portalExtensionType): void
    {
        $this->extensions = $this->extensions->filter(
            static fn (PortalExtensionType $item): bool => !$item->equals($portalExtensionType)
        );
    }

    /**
     * Gets the portal extensions, that must receive a change in their active state.
     */
    public function getExtensions(): PortalExtensionTypeCollection
    {
        return new PortalExtensionTypeCollection($this->extensions);
    }
}
