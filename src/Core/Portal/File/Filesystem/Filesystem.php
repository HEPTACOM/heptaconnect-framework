<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Portal\File\Filesystem;

use Heptacom\HeptaConnect\Core\File\Filesystem\Contract\StreamUriSchemePathConverterInterface;
use Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Contract\FilesystemInterface;

final class Filesystem implements FilesystemInterface
{
    private StreamUriSchemePathConverterInterface $uriSchemePathConverter;

    public function __construct(StreamUriSchemePathConverterInterface $uriSchemePathConverter)
    {
        $this->uriSchemePathConverter = $uriSchemePathConverter;
    }

    public function toStoragePath(string $path): string
    {
        return $this->uriSchemePathConverter->convertToUri($path);
    }

    public function fromStoragePath(string $uri): string
    {
        return $this->uriSchemePathConverter->convertToPath($uri);
    }
}
