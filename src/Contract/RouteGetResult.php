<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\RouteInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class RouteGetResult implements RouteInterface
{
    protected RouteKeyInterface $route;

    protected PortalNodeKeyInterface $source;

    protected PortalNodeKeyInterface $target;

    protected string $entityType;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     */
    public function __construct(
        RouteKeyInterface $route,
        PortalNodeKeyInterface $source,
        PortalNodeKeyInterface $target,
        string $entityType
    ) {
        $this->route = $route;
        $this->source = $source;
        $this->target = $target;
        $this->entityType = $entityType;
    }

    public function getKey(): RouteKeyInterface
    {
        return $this->route;
    }

    public function getTargetKey(): PortalNodeKeyInterface
    {
        return $this->target;
    }

    public function getSourceKey(): PortalNodeKeyInterface
    {
        return $this->source;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }
}
