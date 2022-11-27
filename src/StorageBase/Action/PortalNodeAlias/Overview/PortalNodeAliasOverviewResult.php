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

    public function __construct(
        private PortalNodeKeyInterface $key,
        private string $alias
    ) {
        $this->attachments = new AttachmentCollection();
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
