<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;

interface PortalNodeConfigurationRenderUiActionInterface extends UiActionInterface
{
    /**
     * Gets the configuration for the given portal node after every automatic change has been applied.
     *
     * @throws ReadException
     * @throws PortalNodesMissingException
     *
     * @return iterable<PortalNodeConfigurationRenderResult>
     */
    public function getRendered(PortalNodeConfigurationRenderCriteria $criteria, UiActionContextInterface $context): iterable;
}
