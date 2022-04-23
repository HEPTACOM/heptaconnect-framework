<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalExtensionIsAlreadyInactiveOnPortalNodeException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var class-string<PortalExtensionContract>
     */
    private string $portalExtensionClass;

    /**
     * @param class-string<PortalExtensionContract> $portalExtensionClass
     */
    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        string $portalExtensionClass,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('The given portal extension is already inactive on the portal node key', $code, $previous);
        $this->portalNodeKey = $portalNodeKey;
        $this->portalExtensionClass = $portalExtensionClass;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * @return class-string<PortalExtensionContract>
     */
    public function getPortalExtensionClass(): string
    {
        return $this->portalExtensionClass;
    }
}
