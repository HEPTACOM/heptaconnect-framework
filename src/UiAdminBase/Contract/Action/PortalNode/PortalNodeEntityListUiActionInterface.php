<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult;

interface PortalNodeEntityListUiActionInterface
{
    /**
     * Lists all entity types supported by flow components of the given portal node.
     *
     * @return iterable<PortalNodeEntityListResult>
     */
    public function list(PortalNodeEntityListCriteria $criteria): iterable;
}
