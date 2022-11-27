<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

/**
 * Describes objects that know a primary key.
 * This is a counterpart to @see ForeignKeyAwareInterface
 */
interface PrimaryKeyAwareInterface
{
    /**
     * Returns the primary key of the object.
     */
    public function getPrimaryKey(): ?string;

    /**
     * Sets the primary key of the object.
     */
    public function setPrimaryKey(?string $primaryKey): void;
}
