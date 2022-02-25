<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

final class PortalExtensionFindResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var array<class-string<PortalExtensionContract>, bool>
     */
    private array $extensions = [];

    /**
     * @param class-string<PortalExtensionContract> $class
     */
    public function add(string $class, bool $active): void
    {
        $this->extensions[$class] ??= $active;
    }

    public function isActive(PortalExtensionContract $portalExtension): bool
    {
        return $this->extensions[\get_class($portalExtension)] ?? $portalExtension->isActiveByDefault();
    }
}
