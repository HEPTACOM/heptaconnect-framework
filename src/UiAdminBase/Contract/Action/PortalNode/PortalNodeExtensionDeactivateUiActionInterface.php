<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionDeactivate\PortalNodeExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyInactiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;

interface PortalNodeExtensionDeactivateUiActionInterface
{
    /**
     * Deactivate portal extensions by the given queries on the given portal node.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws NoMatchForPackageQueryException
     * @throws PortalExtensionsAreAlreadyInactiveOnPortalNodeException
     * @throws PortalNodesMissingException
     */
    public function deactivate(PortalNodeExtensionDeactivatePayload $payload): void;
}
