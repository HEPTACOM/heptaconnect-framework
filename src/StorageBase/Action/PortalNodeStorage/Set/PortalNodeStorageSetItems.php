<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set;

use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<PortalNodeStorageSetItem>
 */
final class PortalNodeStorageSetItems extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    #[\Override]
    protected function getT(): string
    {
        return PortalNodeStorageSetItem::class;
    }
}
