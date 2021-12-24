<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\Find;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

class PortalExtensionFindResult
{
    /**
     * @var array<class-string<PortalExtensionContract>, bool>
     */
    private array $extensions = [];

    public function __construct(array $extensions)
    {
        foreach ($extensions as $extension) {
            $class = $extension['class'] ?? null;
            $active = $extension['active'] ?? null;

            if (!\is_string($class)) {
                throw new \Exception('Invalid PortalExtensionFindResult: "class" must be a string.', 1640359045);
            }

            if (!\is_bool($active)) {
                throw new \Exception('Invalid PortalExtensionFindResult: "active" must be a bool.', 1640359052);
            }

            $this->extensions[$class] ??= $active;
        }
    }

    public function isActive(PortalExtensionContract $portalExtension): bool
    {
        return $this->extensions[\get_class($portalExtension)] ?? $portalExtension->isActiveByDefault();
    }
}
