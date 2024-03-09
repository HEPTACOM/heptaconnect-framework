<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

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
