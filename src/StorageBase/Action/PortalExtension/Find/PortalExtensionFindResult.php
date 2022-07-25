<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;

final class PortalExtensionFindResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var array<class-string<PortalExtensionContract>, bool>
     */
    private array $extensions = [];

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
    }

    public function add(PortalExtensionType $portalExtensionType, bool $active): void
    {
        $this->extensions[(string) $portalExtensionType] ??= $active;
    }

    public function isActive(PortalExtensionContract $portalExtension): bool
    {
        return $this->extensions[(string) $portalExtension::class()] ?? $portalExtension->isActiveByDefault();
    }
}
