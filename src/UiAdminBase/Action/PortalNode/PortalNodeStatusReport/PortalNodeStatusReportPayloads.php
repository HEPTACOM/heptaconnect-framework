<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeStatusReportPayloads implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var string[]
     */
    private array $topics;

    /**
     * @param string[] $topics
     */
    public function __construct(PortalNodeKeyInterface $portalNodeKey, array $topics)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->topics = $topics;
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
}
