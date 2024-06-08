<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\File;

use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<FileReferenceContract>
 */
final class FileReferenceCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return FileReferenceContract::class;
    }
}
