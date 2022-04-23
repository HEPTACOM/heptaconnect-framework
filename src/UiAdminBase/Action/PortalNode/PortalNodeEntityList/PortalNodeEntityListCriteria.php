<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeEntityListCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var class-string<DatasetEntityContract>|null
     */
    private ?string $filterSupportedEntityType = null;

    private bool $showExplorer = true;

    private bool $showEmitter = true;

    private bool $showReceiver = true;

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    /**
     * @return class-string<DatasetEntityContract>|null
     */
    public function getFilterSupportedEntityType(): ?string
    {
        return $this->filterSupportedEntityType;
    }

    /**
     * @param class-string<DatasetEntityContract>|null $filterSupportedEntityType
     */
    public function setFilterSupportedEntityType(?string $filterSupportedEntityType): void
    {
        $this->filterSupportedEntityType = $filterSupportedEntityType;
    }

    public function getShowExplorer(): bool
    {
        return $this->showExplorer;
    }

    public function setShowExplorer(bool $showExplorer): void
    {
        $this->showExplorer = $showExplorer;
    }

    public function getShowEmitter(): bool
    {
        return $this->showEmitter;
    }

    public function setShowEmitter(bool $showEmitter): void
    {
        $this->showEmitter = $showEmitter;
    }

    public function getShowReceiver(): bool
    {
        return $this->showReceiver;
    }

    public function setShowReceiver(bool $showReceiver): void
    {
        $this->showReceiver = $showReceiver;
    }
}
