<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract;

final class RouteBrowseCriteria extends BrowseCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private ?ClassStringReferenceCollection $entityTypeFilter = null;

    private ?PortalNodeKeyCollection $sourcePortalNodeKeyFilter = null;

    private ?PortalNodeKeyCollection $targetPortalNodeKeyFilter = null;

    /**
     * @var string[]|null
     */
    private ?array $capabilityFilter = null;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
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

    /**
     * @return string[]|null
     */
    public function getCapabilityFilter(): ?array
    {
        return $this->capabilityFilter;
    }

    /**
     * @param string[]|null $capabilityFilter
     */
    public function setCapabilityFilter(?array $capabilityFilter): void
    {
        $this->capabilityFilter = $capabilityFilter;
    }
}
