<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Audit;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class UiAuditContext implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private string $uiIdentifier,
        private string $userIdentifier
    ) {
        $this->attachments = new AttachmentCollection();
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
