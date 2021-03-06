<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

final class PortalEntityListCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var class-string<PortalContract>
     */
    private string $portal;

    /**
     * @var class-string<DatasetEntityContract>|null
     */
    private ?string $filterSupportedEntityType = null;

    private bool $showExplorer = true;

    private bool $showEmitter = true;

    private bool $showReceiver = true;

    /**
     * @param class-string<PortalContract> $portal
     */
    public function __construct(string $portal)
    {
        $this->attachments = new AttachmentCollection();
        $this->portal = $portal;
    }

    /**
     * @return class-string<PortalContract>
     */
    public function getPortal(): string
    {
        return $this->portal;
    }

    /**
     * @param class-string<PortalContract> $portal
     */
    public function setPortal(string $portal): void
    {
        $this->portal = $portal;
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
