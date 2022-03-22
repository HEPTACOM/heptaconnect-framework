<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

use Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract;

abstract class FileReferenceResolverContract
{
    abstract public function resolve(FileReferenceContract $fileReference): ResolvedFileReferenceContract;
}
