<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

trait ForeignKeyTrait
{
    protected ?string $foreignKey = null;

    public function getForeignKey(): ?string
    {
        return $this->foreignKey;
    }

    public function setForeignKey(?string $foreignKey): void
    {
        $this->foreignKey = $foreignKey;
    }
}
