<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobRunInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobRunKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

/**
 * @internal
 */
abstract class CronjobRunRepositoryContract
{
    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function create(CronjobKeyInterface $cronjobKey, \DateTimeInterface $queuedFor): CronjobRunKeyInterface;

    /**
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobRunKeyInterface>
     */
    abstract public function listExecutables(\DateTimeInterface $now): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function read(CronjobRunKeyInterface $cronjobRunKey): CronjobRunInterface;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function updateStartedAt(CronjobRunKeyInterface $cronjobRunKey, \DateTimeInterface $now): void;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function updateFinishedAt(CronjobRunKeyInterface $cronjobRunKey, \DateTimeInterface $now): void;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function updateFailReason(CronjobRunKeyInterface $cronjobRunKey, \Throwable $throwable): void;
}
