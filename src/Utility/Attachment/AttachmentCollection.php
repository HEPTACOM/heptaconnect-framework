<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Attachment;

use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<AttachableInterface>
 */
final class AttachmentCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return AttachableInterface::class;
    }
}
