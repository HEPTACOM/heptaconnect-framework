<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;

final class PortalNodeAddPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalType $portalClass;

    private ?string $portalNodeAlias = null;

    public function __construct(PortalType $portalClass)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalClass = $portalClass;
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
}
