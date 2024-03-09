<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Contract;

use Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Exception\UnexpectedFormatOfUriException;

/**
 * Supports accessing the portal node specific filesystem.
 */
interface FilesystemInterface
{
    /**
     * Prefixes the path with a portal node unique PHP stream path.
     *
     * @throws UnexpectedFormatOfUriException
     */
    public function toStoragePath(string $path): string;

    /**
     * Removes the portal node unique PHP stream path scheme.
     *
     * @throws UnexpectedFormatOfUriException
     */
    public function fromStoragePath(string $uri): string;
}
