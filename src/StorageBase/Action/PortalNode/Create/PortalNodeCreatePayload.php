<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class PortalNodeCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalType $portalClass;

    private ?string $alias;

    public function __construct(PortalType $portalClass, ?string $alias = null)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalClass = $portalClass;
        $this->alias = $alias;
    }

    public function getPortalClass(): PortalType
    {
        return $this->portalClass;
    }

    public function setPortalClass(PortalType $portalClass): void
    {
        $this->portalClass = $portalClass;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }
}
