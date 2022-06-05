<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalExtensionsAreAlreadyActiveOnPortalNodeException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var class-string<PortalExtensionContract>[]
     */
    private array $portalExtensionClasses;

    /**
     * @param class-string<PortalExtensionContract>[] $portalExtensionClasses
     */
    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        array $portalExtensionClasses,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('The given portal extensions are already active on the portal node key', $code, $previous);
        $this->portalNodeKey = $portalNodeKey;
        $this->portalExtensionClasses = $portalExtensionClasses;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * @return class-string<PortalExtensionContract>[]
     */
    public function getPortalExtensionClasses(): array
    {
        return $this->portalExtensionClasses;
    }
}
