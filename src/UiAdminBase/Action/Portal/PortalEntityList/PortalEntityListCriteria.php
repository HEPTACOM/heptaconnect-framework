<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;

final class PortalEntityListCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalType $portal;

    private ?ClassStringReferenceContract $filterSupportedEntityType = null;

    private bool $showExplorer = true;

    private bool $showEmitter = true;

    private bool $showReceiver = true;

    public function __construct(PortalType $portal)
    {
        $this->attachments = new AttachmentCollection();
        $this->portal = $portal;
    }

    public function getPortal(): PortalType
    {
        return $this->portal;
    }

    public function setPortal(PortalType $portal): void
    {
        $this->portal = $portal;
    }

    public function getFilterSupportedEntityType(): ?ClassStringReferenceContract
    {
        return $this->filterSupportedEntityType;
    }

    public function setFilterSupportedEntityType(?ClassStringReferenceContract $filterSupportedEntityType): void
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
