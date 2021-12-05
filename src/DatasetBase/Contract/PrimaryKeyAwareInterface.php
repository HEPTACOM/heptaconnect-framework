<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface PrimaryKeyAwareInterface
{
    public function getPrimaryKey(): ?string;

    public function setPrimaryKey(?string $primaryKey): void;
}
