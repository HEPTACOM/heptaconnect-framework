<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;

final class PortalExtensionFindResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var array<string, bool>
     */
    private array $extensions = [];

    public function add(ClassStringReferenceContract $portalExtensionType, bool $active): void
    {
        $this->extensions[(string) $portalExtensionType] ??= $active;
    }

    public function isActive(PortalExtensionContract $portalExtension): bool
    {
        return $this->extensions[(string) $portalExtension::class()] ?? $portalExtension->isActiveByDefault();
    }
}
