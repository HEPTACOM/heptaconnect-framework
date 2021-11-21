<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

class RouteCreatePayload implements CreatePayloadInterface
{
    protected PortalNodeKeyInterface $sourcePortalNodeKey;

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
        PortalNodeKeyInterface $sourcePortalNodeKey,
        PortalNodeKeyInterface $target,
        string $entityType,
        array $capabilities = []
    ) {
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
        $this->target = $target;
        $this->entityType = $entityType;
        $this->capabilities = $capabilities;
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    public function setSourcePortalNodeKey(PortalNodeKeyInterface $sourcePortalNodeKey): void
    {
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
    }

    public function getTarget(): PortalNodeKeyInterface
    {
        return $this->target;
    }

    public function setTarget(PortalNodeKeyInterface $target): void
    {
        $this->target = $target;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     */
    public function setEntityType(string $entityType): void
    {
        $this->entityType = $entityType;
    }

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * @param string[] $capabilities
     */
    public function setCapabilities(array $capabilities): void
    {
        $this->capabilities = $capabilities;
    }
}
