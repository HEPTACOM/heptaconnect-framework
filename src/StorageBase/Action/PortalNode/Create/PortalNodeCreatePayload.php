<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class PortalNodeCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var class-string<PortalContract>
     */
    protected string $portalClass;

    protected ?string $alias;

    /**
     * @param class-string<PortalContract> $portalClass
     */
    public function __construct(string $portalClass, ?string $alias = null)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalClass = $portalClass;
        $this->alias = $alias;
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }
}
