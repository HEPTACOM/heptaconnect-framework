<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;
use Heptacom\HeptaConnect\Storage\Base\IdentityDirectionKeyCollection;

final class IdentityDirectionOverviewCriteria extends OverviewCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public const FIELD_CREATED = 'created';

    public const FIELD_ENTITY_TYPE = 'entityType';

    public const FIELD_TARGET_PORTAL_NODE = 'targetPortalNode';

    public const FIELD_TARGET_EXTERNAL_ID = 'targetExternalId';

    public const FIELD_SOURCE_PORTAL_NODE = 'sourcePortalNode';

    public const FIELD_SOURCE_EXTERNAL_ID = 'sourceExternalId';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];

    protected ?StringCollection $entityTypeFilter = null;

    protected ?PortalNodeKeyCollection $sourcePortalNodeKeyFilter = null;

    protected ?PortalNodeKeyCollection $targetPortalNodeKeyFilter = null;

    protected ?StringCollection $sourceExternalIdFilter = null;

    protected ?StringCollection $targetExternalIdFilter = null;

    protected ?IdentityDirectionKeyCollection $identityDirectionKeyFilter = null;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
    }

    public function getSort(): array
    {
        return $this->sort;
    }

    public function setSort(array $sort): void
    {
        $this->sort = $sort;
    }

    public function getEntityTypeFilter(): ?StringCollection
    {
        return $this->entityTypeFilter;
    }

    public function setEntityTypeFilter(?StringCollection $entityTypeFilter): void
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

    public function getSourceExternalIdFilter(): ?StringCollection
    {
        return $this->sourceExternalIdFilter;
    }

    public function setSourceExternalIdFilter(?StringCollection $sourceExternalIdFilter): void
    {
        $this->sourceExternalIdFilter = $sourceExternalIdFilter;
    }

    public function getTargetExternalIdFilter(): ?StringCollection
    {
        return $this->targetExternalIdFilter;
    }

    public function setTargetExternalIdFilter(?StringCollection $targetExternalIdFilter): void
    {
        $this->targetExternalIdFilter = $targetExternalIdFilter;
    }

    public function getIdentityDirectionKeyFilter(): ?IdentityDirectionKeyCollection
    {
        return $this->identityDirectionKeyFilter;
    }

    public function setIdentityDirectionKeyFilter(?IdentityDirectionKeyCollection $identityDirectionKeyFilter): void
    {
        $this->identityDirectionKeyFilter = $identityDirectionKeyFilter;
    }
}
