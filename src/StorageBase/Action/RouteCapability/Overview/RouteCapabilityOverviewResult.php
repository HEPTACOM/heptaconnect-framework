<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class RouteCapabilityOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected string $name;

    protected \DateTimeInterface $createdAt;

    public function __construct(string $name, \DateTimeInterface $createdAt)
    {
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
