<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

/**
 * @extends AbstractObjectCollection<PortalNodeStorageSetItem>
 */
final class PortalNodeStorageSetItems extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return PortalNodeStorageSetItem::class
     */
    protected function getT(): string
    {
        return PortalNodeStorageSetItem::class;
    }
}
