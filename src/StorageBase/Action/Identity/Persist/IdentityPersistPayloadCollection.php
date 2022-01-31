<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayloadContract>
 */
class IdentityPersistPayloadCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return IdentityPersistPayloadContract::class;
    }
}
