<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionDeactivate\PortalNodeExtensionDeactivatePayloads;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionIsAlreadyInactiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionDoesNotSupportPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException;

interface PortalNodeExtensionDeactivateUiActionInterface
{
    /**
     * Deactivate portal extensions on the given portal node.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PortalExtensionIsAlreadyInactiveOnPortalNodeException
     * @throws PortalExtensionMissingException
     * @throws PortalExtensionDoesNotSupportPortalNodeException
     * @throws PortalNodeMissingException
     */
    public function deactivate(PortalNodeExtensionDeactivatePayloads $payloads): void;
}
