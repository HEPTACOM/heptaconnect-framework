<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

final class IdentityOverviewCriteria extends OverviewCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public const FIELD_CREATED = 'created';

    public const FIELD_ENTITY_TYPE = 'entityType';

    public const FIELD_MAPPING_NODE = 'mappingNode';

    public const FIELD_PORTAL_NODE = 'portalNode';

    public const FIELD_EXTERNAL_ID = 'externalId';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];

    /**
     * @var ClassStringReferenceContract[]
     */
    private array $entityTypeFilter = [];

    /**
     * @var string[]
     */
    private array $externalIdFilter = [];

    private PortalNodeKeyCollection $portalNodeKeyFilter;

    private MappingNodeKeyCollection $mappingNodeKeyFilter;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKeyFilter = new PortalNodeKeyCollection();
        $this->mappingNodeKeyFilter = new MappingNodeKeyCollection();
    }

    /**
     * @return ClassStringReferenceContract[]
     */
    public function getEntityTypeFilter(): array
    {
        return $this->entityTypeFilter;
    }

    /**
     * @param ClassStringReferenceContract[] $entityTypeFilter
     */
    public function setEntityTypeFilter(array $entityTypeFilter): void
    {
        $this->entityTypeFilter = $entityTypeFilter;
    }

    /**
     * @return string[]
     */
    public function getExternalIdFilter(): array
    {
        return $this->externalIdFilter;
    }

    /**
     * @param string[] $externalIdFilter
     */
    public function setExternalIdFilter(array $externalIdFilter): void
    {
        $this->externalIdFilter = $externalIdFilter;
    }

    public function getPortalNodeKeyFilter(): PortalNodeKeyCollection
    {
        return $this->portalNodeKeyFilter;
    }

    public function setPortalNodeKeyFilter(PortalNodeKeyCollection $portalNodeKeyFilter): void
    {
        $this->portalNodeKeyFilter = $portalNodeKeyFilter;
    }

    public function getMappingNodeKeyFilter(): MappingNodeKeyCollection
    {
        return $this->mappingNodeKeyFilter;
    }

    public function setMappingNodeKeyFilter(MappingNodeKeyCollection $mappingNodeKeyFilter): void
    {
        $this->mappingNodeKeyFilter = $mappingNodeKeyFilter;
    }
}
