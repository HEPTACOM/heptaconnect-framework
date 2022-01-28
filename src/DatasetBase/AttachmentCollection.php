<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface>
 */
final class AttachmentCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return Contract\AttachableInterface::class;
    }
}
