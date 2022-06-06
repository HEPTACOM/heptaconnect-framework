<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Get;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class RouteGetResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected RouteKeyInterface $routeKey;

    protected PortalNodeKeyInterface $sourcePortalNodeKey;

    protected PortalNodeKeyInterface $targetPortalNodeKey;

    /**
     * @var class-string<DatasetEntityContract>
     */
    protected string $entityType;

    /**
     * @var string[]
     */
    protected array $capabilities;

    /**
     * @param class-string<DatasetEntityContract> $entityType
     * @param string[]                            $capabilities
     */
    public function __construct(
        RouteKeyInterface $routeKey,
        PortalNodeKeyInterface $sourcePortalNodeKey,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $entityType,
        array $capabilities
    ) {
        $this->attachments = new AttachmentCollection();
        $this->routeKey = $routeKey;
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
        $this->targetPortalNodeKey = $targetPortalNodeKey;
        $this->entityType = $entityType;
        $this->capabilities = $capabilities;
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }

    public function getTargetPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->targetPortalNodeKey;
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    /**
     * @return class-string<DatasetEntityContract>
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
