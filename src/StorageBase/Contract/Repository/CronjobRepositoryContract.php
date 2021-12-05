<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

/**
 * @internal
 */
abstract class CronjobRepositoryContract
{
    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract> $handler
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function create(
        PortalNodeKeyInterface $portalNodeKey,
        string $cronExpression,
        string $handler,
        \DateTimeInterface $nextExecution,
        ?array $payload = null
    ): CronjobInterface;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function read(CronjobKeyInterface $cronjobKey): CronjobInterface;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function updateNextExecutionTime(CronjobKeyInterface $cronjobKey, \DateTimeInterface $nextExecution): void;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function delete(CronjobKeyInterface $cronjobKey): void;

    /**
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface>
     */
    abstract public function listExecutables(?\DateTimeInterface $until = null): iterable;
}
