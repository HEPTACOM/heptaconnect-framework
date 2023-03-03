<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeConfigurationRenderResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private array $configuration
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

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNodeKey' => $this->getPortalNodeKey(),
            'configuration' => $this->getConfiguration(),
        ];
    }
}
