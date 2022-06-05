<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionDoesNotSupportPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionIsAlreadyActiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException;

interface PortalNodeExtensionActivateUiActionInterface
{
    /**
     * Activate portal extensions by the given queries on the given portal node.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PortalExtensionIsAlreadyActiveOnPortalNodeException
     * @throws PortalExtensionMissingException
     * @throws PortalExtensionDoesNotSupportPortalNodeException
     * @throws PortalNodeMissingException
     */
    public function activate(PortalNodeExtensionActivatePayload $payload): void;
}
