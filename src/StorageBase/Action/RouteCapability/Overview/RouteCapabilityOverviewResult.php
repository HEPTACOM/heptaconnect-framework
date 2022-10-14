<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class RouteCapabilityOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $name;

    private \DateTimeInterface $createdAt;

    public function __construct(string $name, \DateTimeInterface $createdAt)
    {
        $this->attachments = new AttachmentCollection();
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
