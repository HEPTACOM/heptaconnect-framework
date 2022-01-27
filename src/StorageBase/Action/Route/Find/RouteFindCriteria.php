<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Find;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class RouteFindCriteria
{
    private PortalNodeKeyInterface $source;

    private PortalNodeKeyInterface $target;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    private string $entityType;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     */
    public function __construct(PortalNodeKeyInterface $source, PortalNodeKeyInterface $target, string $entityType)
    {
        $this->source = $source;
        $this->target = $target;
        $this->entityType = $entityType;
    }

    public function getSource(): PortalNodeKeyInterface
    {
        return $this->source;
    }

    public function setSource(PortalNodeKeyInterface $source): void
    {
        $this->source = $source;
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
}
