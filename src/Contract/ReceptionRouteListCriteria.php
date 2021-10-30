<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class ReceptionRouteListCriteria
{
    protected PortalNodeKeyInterface $from;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    protected string $entityType;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     */
    public function __construct(PortalNodeKeyInterface $from, string $entityType)
    {
        $this->from = $from;
        $this->entityType = $entityType;
    }

    public function getFrom(): PortalNodeKeyInterface
    {
        return $this->from;
    }

    public function setFrom(PortalNodeKeyInterface $from): void
    {
        $this->from = $from;
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
