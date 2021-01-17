<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Throwable;

class UnsharableOwnerException extends \Exception
{
    private string $expectedDatasetEntityClassName;

    private ?string $expectedPrimaryKey;

    private DatasetEntityInterface $owner;

    public function __construct(string $expectedDatasetEntityClassName, ?string $expectedPrimaryKey, DatasetEntityInterface $owner, Throwable $previous = null)
    {
        parent::__construct(\sprintf(
                'Owner of class %s with primary key %s does not match %s and %s',
                \get_class($owner),
                $owner->getPrimaryKey(),
                $expectedDatasetEntityClassName,
                $expectedPrimaryKey
            ), 0, $previous);

        $this->expectedDatasetEntityClassName = $expectedDatasetEntityClassName;
        $this->expectedPrimaryKey = $expectedPrimaryKey;
        $this->owner = $owner;
    }

    public function getExpectedDatasetEntityClassName(): string
    {
        return $this->expectedDatasetEntityClassName;
    }

    public function getExpectedPrimaryKey(): ?string
    {
        return $this->expectedPrimaryKey;
    }

    public function getOwner(): DatasetEntityInterface
    {
        return $this->owner;
    }
}
