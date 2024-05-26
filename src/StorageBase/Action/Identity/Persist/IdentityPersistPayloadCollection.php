<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<IdentityPersistPayloadContract>
 */
final class IdentityPersistPayloadCollection extends AbstractObjectCollection
{
    #[\Override]
    protected function getT(): string
    {
        return IdentityPersistPayloadContract::class;
    }
}
