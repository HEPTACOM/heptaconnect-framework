<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Exception;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeServiceExceptionInterface;

final class StorageFacadeServiceException extends \Exception implements StorageFacadeServiceExceptionInterface
{
    private string $service;

    public function __construct(string $service, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('Failed to get storage facade service %s', $service), 1645567423, $previous);
        $this->service = $service;
    }

    public function getService(): string
    {
        return $this->service;
    }
}
