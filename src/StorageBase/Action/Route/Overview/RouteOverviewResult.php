<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class RouteOverviewResult
{
    protected RouteKeyInterface $routeKey;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    protected string $entityType;

    protected PortalNodeKeyInterface $sourcePortalNodeKey;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    protected string $sourcePortalClass;

    protected PortalNodeKeyInterface $targetPortalNodeKey;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    protected string $targetPortalClass;

    protected \DateTimeInterface $createdAt;

    /**
     * @var string[]
     */
    private array $capabilities;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>  $sourcePortalClass
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>  $targetPortalClass
     * @param string[]                                                                         $capabilities
     */
    public function __construct(
        RouteKeyInterface $routeKey,
        string $entityType,
        PortalNodeKeyInterface $sourcePortalNodeKey,
        string $sourcePortalClass,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $targetPortalClass,
        \DateTimeInterface $createdAt,
        array $capabilities
    ) {
        $this->routeKey = $routeKey;
        $this->entityType = $entityType;
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
        $this->sourcePortalClass = $sourcePortalClass;
        $this->targetPortalNodeKey = $targetPortalNodeKey;
        $this->targetPortalClass = $targetPortalClass;
        $this->createdAt = $createdAt;
        $this->capabilities = $capabilities;
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public function getSourcePortalClass(): string
    {
        return $this->sourcePortalClass;
    }

    public function getTargetPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->targetPortalNodeKey;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public function getTargetPortalClass(): string
    {
        return $this->targetPortalClass;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }
}
