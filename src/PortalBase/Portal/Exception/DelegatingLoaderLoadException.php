<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Exception;

final class DelegatingLoaderLoadException extends \Exception
{
    public function __construct(
        private readonly string $path,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            \sprintf('Exception when loading container service file from path %s', $this->path),
            $code,
            $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
