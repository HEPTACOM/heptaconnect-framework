<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreateResult>
 */
final class IdentityErrorCreateResults extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return IdentityErrorCreateResult::class;
    }
}
