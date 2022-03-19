<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

trait PrimaryKeyTrait
{
    protected ?string $primaryKey = null;

    public function getPrimaryKey(): ?string
    {
        return $this->primaryKey;
    }

    public function setPrimaryKey(?string $primaryKey): void
    {
        $this->primaryKey = $primaryKey;
    }
}
