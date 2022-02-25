<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalExtensionActivatePayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var array<class-string<PortalExtensionContract>, bool>
     */
    private array $extensions = [];

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * @param class-string<PortalExtensionContract> $extensionClass
     */
    public function addExtension(string $extensionClass): void
    {
        $this->extensions[$extensionClass] = true;
    }

    /**
     * @param class-string<PortalExtensionContract> $extensionClass
     */
    public function removeExtension(string $extensionClass): void
    {
        unset($this->extensions[$extensionClass]);
    }

    /**
     * @return array<class-string<PortalExtensionContract>>
     */
    public function getExtensions(): array
    {
        return \array_keys($this->extensions);
    }
}
