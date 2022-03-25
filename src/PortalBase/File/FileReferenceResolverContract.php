<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

use Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract;

abstract class FileReferenceResolverContract
{
    /**
     * Resolves a given file reference to prepare it for read operations. A file reference from any portal node context
     * can be resolved in either the same or in any other portal node context.
     */
    abstract public function resolve(FileReferenceContract $fileReference): ResolvedFileReferenceContract;
}
