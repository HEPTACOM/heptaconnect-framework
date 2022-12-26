<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

/**
 * @extends AbstractObjectCollection<PortalNodeAliasSetPayload>
 */
final class PortalNodeAliasSetPayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param iterable<PortalNodeAliasSetPayload> $items
     */
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
        $this->attachments = new AttachmentCollection();
    }

    protected function getT(): string
    {
        return PortalNodeAliasSetPayload::class;
    }
}
