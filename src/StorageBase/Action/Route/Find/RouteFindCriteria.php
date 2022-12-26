<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Find;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class RouteFindCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $source,
        private PortalNodeKeyInterface $target,
        private ClassStringReferenceContract $entityType
    ) {
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

    public function getEntityType(): ClassStringReferenceContract
    {
        return $this->entityType;
    }

    public function setEntityType(ClassStringReferenceContract $entityType): void
    {
        $this->entityType = $entityType;
    }
}
