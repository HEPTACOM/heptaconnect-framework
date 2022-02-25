<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

abstract class ResolvedFileReferenceContract
{
    abstract public function getPublicUrl(): string;

    // TODO: Add getPresignedUrl() for limited permissions
    // abstract public function getPresignedUrl(): string;

    abstract public function getContents(): string;
}
