<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType;

final class UiAuditTrailBeginPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private \DateTimeImmutable $at;

    private string $uiType;

    private string $uiIdentifier;

    private UiActionType $uiActionType;

    private string $userIdentifier;

    private TaggedStringCollection $arguments;

    public function __construct(
        string $uiType,
        string $uiIdentifier,
        UiActionType $uiActionType,
        string $userIdentifier,
        TaggedStringCollection $arguments
    ) {
        $this->attachments = new AttachmentCollection();
        $this->at = new \DateTimeImmutable();
        $this->uiType = $uiType;
        $this->uiIdentifier = $uiIdentifier;
        $this->uiActionType = $uiActionType;
        $this->userIdentifier = $userIdentifier;
        $this->arguments = $arguments;
    }

    public function getAt(): \DateTimeImmutable
    {
        return $this->at;
    }

    public function setAt(\DateTimeImmutable $at): void
    {
        $this->at = $at;
    }

    public function getUiType(): string
    {
        return $this->uiType;
    }

    public function setUiType(string $uiType): void
    {
        $this->uiType = $uiType;
    }

    public function getUiIdentifier(): string
    {
        return $this->uiIdentifier;
    }

    public function setUiIdentifier(string $uiIdentifier): void
    {
        $this->uiIdentifier = $uiIdentifier;
    }

    public function getUiActionType(): UiActionType
    {
        return $this->uiActionType;
    }

    public function setUiActionType(UiActionType $uiActionType): void
    {
        $this->uiActionType = $uiActionType;
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function setUserIdentifier(string $userIdentifier): void
    {
        $this->userIdentifier = $userIdentifier;
    }

    public function getArguments(): TaggedStringCollection
    {
        return $this->arguments;
    }

    public function setArguments(TaggedStringCollection $arguments): void
    {
        $this->arguments = $arguments;
    }
}
