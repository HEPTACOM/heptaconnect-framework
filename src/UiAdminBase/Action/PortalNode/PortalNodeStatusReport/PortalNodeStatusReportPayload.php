<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeStatusReportPayload implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param string[] $topics
     */
    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private array $topics
    ) {
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
     * @return string[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param string[] $topics
     */
    public function setTopics(array $topics): void
    {
        $this->topics = $topics;
    }

    public function getAuditableData(): array
    {
        return [
            'topics' => $this->getTopics(),
            'portalNode' => $this->getPortalNodeKey(),
        ];
    }
}
