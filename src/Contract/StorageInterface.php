<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

interface StorageInterface
{
    /**
     * @throws StorageMethodNotImplemented
     */
    public function addMappingException(MappingInterface $mapping, \Throwable $throwable): void;

    /**
     * @psalm-param class-string<\Throwable> $type
     *
     * @throws StorageMethodNotImplemented
     */
    public function removeMappingException(MappingInterface $mapping, string $type): void;
}
