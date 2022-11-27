<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeStatusReportResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private string $topic,
        private bool $success,
        private array $payload
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNode' => $this->getPayload(),
            'topic' => $this->getTopic(),
            'success' => $this->getSuccess(),
            'payload' => $this->getPayload(),
        ];
    }
}
