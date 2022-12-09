<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

/**
 * @phpstan-type TDirection RouteBrowseCriteria::SORT_ASC|RouteBrowseCriteria::SORT_DESC
 * @phpstan-type TField RouteBrowseCriteria::FIELD_CREATED|RouteBrowseCriteria::FIELD_ENTITY_TYPE|RouteBrowseCriteria::FIELD_SOURCE|RouteBrowseCriteria::FIELD_TARGET
 */
final class RouteBrowseCriteria extends BrowseCriteriaContract implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public const FIELD_CREATED = 'created';

    public const FIELD_ENTITY_TYPE = 'entityType';

    public const FIELD_SOURCE = 'source';

    public const FIELD_TARGET = 'target';

    /**
     * @var array<string, string>
     *
     * @phpstan-var array<TField, TDirection>
     */
    private array $sort = [
        self::FIELD_CREATED => self::SORT_DESC,
    ];

    private ?ClassStringReferenceCollection $entityTypeFilter = null;

    private ?PortalNodeKeyCollection $sourcePortalNodeKeyFilter = null;

    private ?PortalNodeKeyCollection $targetPortalNodeKeyFilter = null;

    private ?StringCollection $capabilityFilter = null;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
    }

    /**
     * @return array<string, string>
     *
     * @phpstan-return array<TField, TDirection>
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param array<string, string> $sort
     *
     * @phpstan-param array<TField, TDirection> $sort
     */
    public function setSort(array $sort): void
    {
        $this->sort = $sort;
    }

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

    public function getAuditableData(): array
    {
        return [
            'capabilityFilter' => $this->getCapabilityFilter(),
            'entityTypeFilter' => $this->getEntityTypeFilter(),
            'sort' => $this->getSort(),
            'sourcePortalNodeKeyFilter' => $this->getSourcePortalNodeKeyFilter(),
            'targetPortalNodeKeyFilter' => $this->getTargetPortalNodeKeyFilter(),
        ];
    }
}
