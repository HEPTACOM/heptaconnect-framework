<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface DatasetEntityInterface extends \JsonSerializable
{
    public function getPrimaryKey(): string;

    public function setPrimaryKey(string $primaryKey): void;
}
