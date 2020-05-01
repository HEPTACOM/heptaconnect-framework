<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\DependencyCollection;

interface DatasetEntityInterface extends \JsonSerializable
{
    public function getDependencies(): DependencyCollection;

    public function getPrimaryKey(): string;

    public function setPrimaryKey(string $primaryKey): void;
}
