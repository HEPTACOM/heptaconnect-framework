<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity as T;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @method        __construct(T ...$items)
 * @method T|null offsetGet(int|string $key)
 * @method T|null first()
 * @method T|null last()
 */
class UsageStructCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return T::class;
    }
}
