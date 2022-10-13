<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Portal;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

interface PortalEntityListUiActionInterface extends UiActionInterface
{
    /**
     * Lists all entity types supported by flow components of a fresh stack based on the given criteria.
     *
     * @return iterable<PortalEntityListResult>
     */
    public function list(PortalEntityListCriteria $criteria, UiActionContextInterface $context): iterable;
}
