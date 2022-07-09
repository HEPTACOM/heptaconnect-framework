<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;

interface ForeignKeyAwareInterface
{
    public function getForeignEntityType(): EntityType;

    public function getForeignKey(): ?string;

    public function setForeignKey(?string $foreignKey): void;
}
