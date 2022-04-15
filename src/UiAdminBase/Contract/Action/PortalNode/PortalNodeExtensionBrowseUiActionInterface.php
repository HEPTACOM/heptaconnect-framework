<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult;

interface PortalNodeExtensionBrowseUiActionInterface
{
    /**
     * Lists all portal extensions on the given portal node.
     *
     * @return iterable<PortalNodeExtensionBrowseResult>
     */
    public function browse(PortalNodeExtensionBrowseCriteria $criteria): iterable;
}
