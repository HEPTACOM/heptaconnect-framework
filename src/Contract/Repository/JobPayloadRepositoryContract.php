<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Storage\Base\Contract\JobPayloadKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class JobPayloadRepositoryContract
{
    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return JobPayloadKeyInterface[]
     */
    abstract public function add(array $payloads): array;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function remove(JobPayloadKeyInterface $jobPayloadKey): void;

    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return \Heptacom\HeptaConnect\Storage\Base\Contract\JobPayloadKeyInterface[]
     * @psalm-return iterable<\Heptacom\HeptaConnect\Storage\Base\Contract\JobPayloadKeyInterface>
     */
    abstract public function list(): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function has(JobPayloadKeyInterface $jobPayloadKey): bool;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function get(JobPayloadKeyInterface $jobPayloadKey): array;

    abstract public function cleanup(): void;
}
