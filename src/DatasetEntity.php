<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

abstract class DatasetEntity implements Contract\DatasetEntityInterface
{
    use Support\DependencyAwareTrait;
    use Support\JsonSerializeObjectVarsTrait;
    use Support\PrimaryKeyTrait;
}
