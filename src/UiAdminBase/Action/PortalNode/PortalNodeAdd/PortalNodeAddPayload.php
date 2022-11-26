<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeAddPayload implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    private ?string $portalNodeAlias = null;

    public function __construct(
        private PortalType $portalClass
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getPortalClass(): PortalType
    {
        return $this->portalClass;
    }

    public function setPortalClass(PortalType $portalClass): void
    {
        $this->portalClass = $portalClass;
    }

    public function getPortalNodeAlias(): ?string
    {
        return $this->portalNodeAlias;
    }

    public function setPortalNodeAlias(?string $portalNodeAlias): void
    {
        $this->portalNodeAlias = $portalNodeAlias;
    }

    public function getAuditableData(): array
    {
        return [
            'portalType' => $this->getPortalClass(),
            'alias' => $this->getPortalNodeAlias(),
        ];
    }
}
