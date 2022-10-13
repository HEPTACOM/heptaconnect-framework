<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
final class PortalExtensionsAreAlreadyInactiveOnPortalNodeException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private PortalNodeKeyInterface $portalNodeKey;

    private PortalExtensionTypeCollection $portalExtensionTypes;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        PortalExtensionTypeCollection $portalExtensionTypes,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('The given portal extensions are already inactive on the portal node key', $code, $previous);
        $this->portalNodeKey = $portalNodeKey;
        $this->portalExtensionTypes = $portalExtensionTypes;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getPortalExtensionTypes(): PortalExtensionTypeCollection
    {
        return $this->portalExtensionTypes;
    }
}
