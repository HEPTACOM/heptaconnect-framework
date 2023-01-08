<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListCriteriaContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeEntityListCriteria extends EntityListCriteriaContract implements AuditableDataAwareInterface
{
    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey
    ) {
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNode' => $this->getPortalNodeKey(),
            'filterSupportedEntityType' => $this->getFilterSupportedEntityType(),
            'showExplorer' => $this->getShowExplorer(),
            'showEmitter' => $this->getShowEmitter(),
            'showReceiver' => $this->getShowReceiver(),
        ];
    }
}
