<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyActiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;

interface PortalNodeExtensionActivateUiActionInterface extends UiActionInterface
{
    /**
     * Activate portal extensions by the given queries on the given portal node.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws NoMatchForPackageQueryException
     * @throws PortalExtensionsAreAlreadyActiveOnPortalNodeException
     * @throws PortalNodesMissingException
     */
    public function activate(PortalNodeExtensionActivatePayload $payload, UiActionContextInterface $context): void;
}
