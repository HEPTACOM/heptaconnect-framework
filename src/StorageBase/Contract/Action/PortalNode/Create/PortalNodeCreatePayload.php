<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Create;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

class PortalNodeCreatePayload implements CreatePayloadInterface
{
    /**
     * @var class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    protected string $portalClass;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract> $portalClass
     */
    public function __construct(string $portalClass)
    {
        $this->portalClass = $portalClass;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public function getPortalClass(): string
    {
        return $this->portalClass;
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract> $portalClass
     */
    public function setPortalClass(string $portalClass): void
    {
        $this->portalClass = $portalClass;
    }
}
