<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview;

use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

final class RouteOverviewCriteria extends OverviewCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public const FIELD_TARGET = 'target';

    public const FIELD_SOURCE = 'source';

    public const FIELD_ENTITY_TYPE = 'entityType';

    public const FIELD_CREATED = 'created';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];

    private ?ClassStringReferenceCollection $entityTypeFilter = null;

    private ?PortalNodeKeyCollection $sourcePortalNodeKeyFilter = null;

    private ?PortalNodeKeyCollection $targetPortalNodeKeyFilter = null;

    private ?StringCollection $capabilityFilter = null;

    public function getEntityTypeFilter(): ?ClassStringReferenceCollection
    {
        return $this->entityTypeFilter;
    }

    public function setEntityTypeFilter(?ClassStringReferenceCollection $entityTypeFilter): void
    {
        $this->entityTypeFilter = $entityTypeFilter;
    }

    public function getSourcePortalNodeKeyFilter(): ?PortalNodeKeyCollection
    {
        return $this->sourcePortalNodeKeyFilter;
    }

    public function setSourcePortalNodeKeyFilter(?PortalNodeKeyCollection $sourcePortalNodeKeyFilter): void
    {
        $this->sourcePortalNodeKeyFilter = $sourcePortalNodeKeyFilter;
    }

    public function getTargetPortalNodeKeyFilter(): ?PortalNodeKeyCollection
    {
        return $this->targetPortalNodeKeyFilter;
    }

    public function setTargetPortalNodeKeyFilter(?PortalNodeKeyCollection $targetPortalNodeKeyFilter): void
    {
        $this->targetPortalNodeKeyFilter = $targetPortalNodeKeyFilter;
    }

    public function getCapabilityFilter(): ?StringCollection
    {
        return $this->capabilityFilter;
    }

    public function setCapabilityFilter(?StringCollection $capabilityFilter): void
    {
        $this->capabilityFilter = $capabilityFilter;
    }
}
