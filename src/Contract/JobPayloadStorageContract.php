<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class JobPayloadStorageContract
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function add(object $payload): JobPayloadKeyInterface;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function remove(JobPayloadKeyInterface $processPayloadKey): void;

    /**
     * @return \Heptacom\HeptaConnect\Storage\Base\Contract\JobPayloadKeyInterface[]
     * @psalm-return iterable<\Heptacom\HeptaConnect\Storage\Base\Contract\ProcessPayloadKeyInterface>
     *@throws UnsupportedStorageKeyException
     *
     */
    abstract public function list(): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function has(JobPayloadKeyInterface $processPayloadKey): bool;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function get(JobPayloadKeyInterface $processPayloadKey): object;
}
