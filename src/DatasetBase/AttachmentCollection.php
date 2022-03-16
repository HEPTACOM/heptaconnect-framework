<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

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
