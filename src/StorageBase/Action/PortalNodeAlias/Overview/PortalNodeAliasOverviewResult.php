<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeAliasOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $key;

    private string $alias;

    public function __construct(PortalNodeKeyInterface $key, string $alias)
    {
        $this->attachments = new AttachmentCollection();
        $this->key = $key;
        $this->alias = $alias;
    }

    public function getKey(): PortalNodeKeyInterface
    {
        return $this->key;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
