<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Ui\Admin\Action;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Portal\PortalEntityListUiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeEntityListUiActionInterface;

final class PortalEntityListUi implements PortalEntityListUiActionInterface
{
    private PortalNodeEntityListUiActionInterface $portalNodeEntityListUiAction;

    public function __construct(PortalNodeEntityListUiActionInterface $portalNodeEntityListUiAction)
    {
        $this->portalNodeEntityListUiAction = $portalNodeEntityListUiAction;
    }

    public function list(PortalEntityListCriteria $criteria): iterable
    {
        $portalNodeKey = new PreviewPortalNodeKey($criteria->getPortal());

        $portalNodeCriteria = new PortalNodeEntityListCriteria($portalNodeKey);
        $portalNodeCriteria->setShowEmitter($criteria->getShowEmitter());
        $portalNodeCriteria->setShowExplorer($criteria->getShowExplorer());
        $portalNodeCriteria->setShowReceiver($criteria->getShowReceiver());
        $portalNodeCriteria->setFilterSupportedEntityType($criteria->getFilterSupportedEntityType());

        return \iterable_map(
            $this->portalNodeEntityListUiAction->list($portalNodeCriteria),
            static fn (PortalNodeEntityListResult $r) => new PortalEntityListResult(
                $r->getCodeOrigin(),
                $r->getSupportedEntityType(),
                $r->getFlowComponentClass()
            )
        );
    }
}
