<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

abstract class ResolvedFileReferenceContract
{
    // TODO: Add DateInterval for TTL
    abstract public function getPublicUrl(): string;

    abstract public function getContents(): string;
}
