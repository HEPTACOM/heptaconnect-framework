<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class RouteOverviewResult
{
    protected RouteKeyInterface $route;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    protected string $entityType;

    protected PortalNodeKeyInterface $source;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    protected string $sourceClass;

    protected PortalNodeKeyInterface $target;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    protected string $targetClass;

    protected \DateTimeInterface $createdAt;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract> $sourceClass
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract> $targetClass
     */
    public function __construct(
        RouteKeyInterface $route,
        string $entityType,
        PortalNodeKeyInterface $source,
        string $sourceClass,
        PortalNodeKeyInterface $target,
        string $targetClass,
        \DateTimeInterface $createdAt
    ) {
        $this->route = $route;
        $this->entityType = $entityType;
        $this->source = $source;
        $this->sourceClass = $sourceClass;
        $this->target = $target;
        $this->targetClass = $targetClass;
        $this->createdAt = $createdAt;
    }

    public function getRoute(): RouteKeyInterface
    {
        return $this->route;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getSource(): PortalNodeKeyInterface
    {
        return $this->source;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public function getSourceClass(): string
    {
        return $this->sourceClass;
    }

    public function getTarget(): PortalNodeKeyInterface
    {
        return $this->target;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
