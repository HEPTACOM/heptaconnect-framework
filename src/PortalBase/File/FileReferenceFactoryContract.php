<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

use Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract;
use Psr\Http\Message\RequestInterface;

abstract class FileReferenceFactoryContract
{
    abstract public function fromPublicUrl(string $publicUrl): FileReferenceContract;

    abstract public function fromRequest(RequestInterface $request): FileReferenceContract;

    abstract public function fromContents(
        string $contents,
        string $mimeType = 'application/octet-stream'
    ): FileReferenceContract;
}
