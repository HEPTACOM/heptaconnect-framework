<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Get;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class RouteGetResult
{
    protected RouteKeyInterface $routeKey;

    protected PortalNodeKeyInterface $source;

    protected PortalNodeKeyInterface $target;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    protected string $entityType;

    /**
     * @var string[]
     */
    protected array $capabilities;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     * @param string[]                                                                         $capabilities
     */
    public function __construct(
        RouteKeyInterface $routeKey,
        PortalNodeKeyInterface $source,
        PortalNodeKeyInterface $target,
        string $entityType,
        array $capabilities
    ) {
        $this->routeKey = $routeKey;
        $this->source = $source;
        $this->target = $target;
        $this->entityType = $entityType;
        $this->capabilities = $capabilities;
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
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

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }
}
