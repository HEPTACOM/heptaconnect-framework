<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

class UnsharableOwnerException extends \Exception
{
    private string $expectedEntityType;

    private ?string $expectedPrimaryKey;

    private DatasetEntityContract $owner;

    public function __construct(string $expectedEntityType, ?string $expectedPrimaryKey, DatasetEntityContract $owner, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf(
            'Owner of class %s with primary key %s does not match %s and %s',
            \get_class($owner),
            $owner->getPrimaryKey() ?? '<null>',
            $expectedEntityType,
            $expectedPrimaryKey ?? '<null>'
        ), 0, $previous);

        $this->expectedEntityType = $expectedEntityType;
        $this->expectedPrimaryKey = $expectedPrimaryKey;
        $this->owner = $owner;
    }

    public function getExpectedEntityType(): string
    {
        return $this->expectedEntityType;
    }

    public function getExpectedPrimaryKey(): ?string
    {
        return $this->expectedPrimaryKey;
    }

    public function getOwner(): DatasetEntityContract
    {
        return $this->owner;
    }
}
