<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
 */
class AttachmentCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return Contract\DatasetEntityInterface::class;
    }
}
