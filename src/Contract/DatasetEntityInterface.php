<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\DependencyCollection;

interface DatasetEntityInterface extends \JsonSerializable
{
    public function getDependencies(): DependencyCollection;

    public function getPrimaryKey(): KeyInterface;

    public function setPrimaryKey(KeyInterface $primaryKey): void;
}
