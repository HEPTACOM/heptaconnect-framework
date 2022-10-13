<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

interface PortalNodeEntityListUiActionInterface extends UiActionInterface
{
    /**
     * Lists all entity types supported by flow components of the given portal node.
     *
     * @return iterable<PortalNodeEntityListResult>
     */
    public function list(PortalNodeEntityListCriteria $criteria, UiActionContextInterface $context): iterable;
}
