<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidSubtypeClassNameException;
use Heptacom\HeptaConnect\Utility\ClassString\Exception\UnexpectedLeadingNamespaceSeparatorInClassNameException;

final class TypedDatasetEntityCollection extends DatasetEntityCollection
{
    private readonly EntityType $type;

    /**
     * @phpstan-param class-string<DatasetEntityContract>|EntityType $type
     *
     * @param iterable<DatasetEntityContract> $items
     *
     * @throws InvalidClassNameException
     * @throws InvalidSubtypeClassNameException
     * @throws UnexpectedLeadingNamespaceSeparatorInClassNameException
     */
    public function __construct($type, iterable $items = [])
    {
        $this->type = new EntityType((string) $type);

        parent::__construct($items);
    }

    /**
     * @deprecated use @see getEntityType instead
     *
     * @phpstan-return class-string<DatasetEntityContract>
     */
    public function getType(): string
    {
        return (string) $this->type;
    }

    public function getEntityType(): EntityType
    {
        return $this->type;
    }

    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return parent::isValidItem($item) && $this->type->isObjectOfType($item);
    }
}
