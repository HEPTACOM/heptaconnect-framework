<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class RouteAddResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private RouteKeyInterface $routeKey;

    private PortalNodeKeyInterface $sourcePortalNodeKey;

    private PortalNodeKeyInterface $targetPortalNodeKey;

    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $entityType;

    /**
     * @var string[]
     */
    private array $capabilities;

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

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    public function getTargetPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->targetPortalNodeKey;
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
