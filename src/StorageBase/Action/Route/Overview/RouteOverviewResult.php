<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class RouteOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected RouteKeyInterface $routeKey;

    protected ClassStringReferenceContract $entityType;

    protected PortalNodeKeyInterface $sourcePortalNodeKey;

    /**
     * @var class-string<PortalContract>
     */
    protected string $sourcePortalClass;

    protected PortalNodeKeyInterface $targetPortalNodeKey;

    /**
     * @var class-string<PortalContract>
     */
    protected string $targetPortalClass;

    protected \DateTimeInterface $createdAt;

    /**
     * @var string[]
     */
    private array $capabilities;

    /**
     * @param class-string<PortalContract> $sourcePortalClass
     * @param class-string<PortalContract> $targetPortalClass
     * @param string[]                     $capabilities
     */
    public function __construct(
        RouteKeyInterface $routeKey,
        ClassStringReferenceContract $entityType,
        PortalNodeKeyInterface $sourcePortalNodeKey,
        string $sourcePortalClass,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $targetPortalClass,
        \DateTimeInterface $createdAt,
        array $capabilities
    ) {
        $this->attachments = new AttachmentCollection();
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

    public function getEntityType(): ClassStringReferenceContract
    {
        return $this->entityType;
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    /**
     * @return class-string<PortalContract>
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
     * @return class-string<PortalContract>
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
