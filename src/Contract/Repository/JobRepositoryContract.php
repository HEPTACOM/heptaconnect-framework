<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Storage\Base\Contract\JobInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\Repository\JobAdd;

abstract class JobRepositoryContract
{
    /**
     * @param JobAdd[] $jobAdds
     *
     * @return JobKeyInterface[]
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function add(array $jobAdds): array;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function remove(JobKeyInterface $jobKey): void;

    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return \Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface[]
     * @psalm-return iterable<\Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface>
     */
    abstract public function list(): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function has(JobKeyInterface $jobKey): bool;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function get(JobKeyInterface $jobKey): JobInterface;
}
