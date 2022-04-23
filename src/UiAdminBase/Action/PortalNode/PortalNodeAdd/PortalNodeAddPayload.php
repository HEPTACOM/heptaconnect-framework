<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

final class PortalNodeAddPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var class-string<PortalContract>
     */
    private string $portalClass;

    private ?string $portalNodeAlias = null;

    /**
     * @param class-string<PortalContract> $portalClass
     */
    public function __construct(string $portalClass)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalClass = $portalClass;
    }

    /**
     * @return class-string<PortalContract>
     */
    public function getPortalClass(): string
    {
        return $this->portalClass;
    }

    /**
     * @param class-string<PortalContract> $portalClass
     */
    public function setPortalClass(string $portalClass): void
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
