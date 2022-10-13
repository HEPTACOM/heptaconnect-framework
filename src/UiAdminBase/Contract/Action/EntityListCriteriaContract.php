<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

abstract class EntityListCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private ?ClassStringReferenceContract $filterSupportedEntityType = null;

    private bool $showExplorer = true;

    private bool $showEmitter = true;

    private bool $showReceiver = true;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
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
