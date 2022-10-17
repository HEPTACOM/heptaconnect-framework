<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList;

use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListCriteriaContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalEntityListCriteria extends EntityListCriteriaContract implements AuditableDataAwareInterface
{
    public function __construct(private PortalType $portal)
    {
        parent::__construct();
    }

    public function getPortal(): PortalType
    {
        return $this->portal;
    }

    public function setPortal(PortalType $portal): void
    {
        $this->portal = $portal;
    }

    public function getAuditableData(): array
    {
        return [
            'portal' => $this->getPortal(),
            'filterSupportedEntityType' => $this->getFilterSupportedEntityType(),
            'showExplorer' => $this->getShowExplorer(),
            'showEmitter' => $this->getShowEmitter(),
            'showReceiver' => $this->getShowReceiver(),
        ];
    }
}
