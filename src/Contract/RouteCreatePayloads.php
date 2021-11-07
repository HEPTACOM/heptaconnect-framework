<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCreatePayload>
 */
class RouteCreatePayloads extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return RouteCreatePayload::class;
    }
}
