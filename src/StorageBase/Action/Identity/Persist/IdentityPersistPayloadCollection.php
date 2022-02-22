<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<IdentityPersistPayloadContract>
 */
final class IdentityPersistPayloadCollection extends AbstractObjectCollection
{
    /**
     * @psalm-return IdentityPersistPayloadContract::class
     */
    protected function getT(): string
    {
        return IdentityPersistPayloadContract::class;
    }
}
