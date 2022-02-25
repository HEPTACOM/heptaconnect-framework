<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Find;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class RouteFindCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $source;

    private PortalNodeKeyInterface $target;

    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $entityType;

    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function __construct(PortalNodeKeyInterface $source, PortalNodeKeyInterface $target, string $entityType)
    {
        $this->attachments = new AttachmentCollection();
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
     * @return class-string<DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function setEntityType(string $entityType): void
    {
        $this->entityType = $entityType;
    }
}
