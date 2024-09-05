<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<FirstEntity>
 */
final class FirstEntityCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return FirstEntity::class;
    }
}
