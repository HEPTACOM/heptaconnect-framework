<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Portal\File\Filesystem;

use Heptacom\HeptaConnect\Core\File\Filesystem\Contract\StreamUriSchemePathConverterInterface;
use Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Contract\FilesystemInterface;

final readonly class Filesystem implements FilesystemInterface
{
    public function __construct(
        private StreamUriSchemePathConverterInterface $uriSchemePathConverter
    ) {
    }

    #[\Override]
    public function toStoragePath(string $path): string
    {
        return $this->uriSchemePathConverter->convertToUri($path);
    }

    #[\Override]
    public function fromStoragePath(string $uri): string
    {
        return $this->uriSchemePathConverter->convertToPath($uri);
    }
}
