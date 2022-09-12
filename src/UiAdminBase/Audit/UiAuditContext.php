<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Audit;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class UiAuditContext implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $uiIdentifier;

    private string $userIdentifier;

    public function __construct(string $uiIdentifier, string $userIdentifier)
    {
        $this->attachments = new AttachmentCollection();
        $this->uiIdentifier = $uiIdentifier;
        $this->userIdentifier = $userIdentifier;
    }

    public function getUiIdentifier(): string
    {
        return $this->uiIdentifier;
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }
}
