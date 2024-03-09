<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;

/**
 * Describes objects that know a foreign key.
 * This is a counterpart to @see PrimaryKeyAwareInterface
 */
interface ForeignKeyAwareInterface
{
    /**
     * Returns the class of the @see DatasetEntityContract that is referred to.
     */
    public function getForeignEntityType(): EntityType;

    /**
     * Returns the primary key of the @see DatasetEntityContract that is referred to.
     * If null is returned, the reference is invalid.
     */
    public function getForeignKey(): ?string;

    /**
     * Sets the primary key of the @see DatasetEntityContract to refer to it.
     */
    public function setForeignKey(?string $foreignKey): void;
}
