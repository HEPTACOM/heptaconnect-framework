<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayloads;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionIsAlreadyActiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionDoesNotSupportPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException;

interface PortalNodeExtensionActivateUiActionInterface
{
    /**
     * Activate portal extensions on the given portal node.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PortalExtensionIsAlreadyActiveOnPortalNodeException
     * @throws PortalExtensionMissingException
     * @throws PortalExtensionDoesNotSupportPortalNodeException
     * @throws PortalNodeMissingException
     */
    public function activate(PortalNodeExtensionActivatePayloads $payloads): void;
}
