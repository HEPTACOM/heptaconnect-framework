<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Exception;

final class DelegatingLoaderLoadException extends \Exception
{
    private string $path;

    public function __construct(string $path, int $code, ?\Throwable $previous = null)
    {
        $this->path = $path;

        parent::__construct(
            \sprintf('Exception when loading container service file from path %s', $path),
            $code,
            $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
