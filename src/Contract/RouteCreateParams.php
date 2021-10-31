<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCreateParam>
 */
class RouteCreateParams extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return RouteCreateParam::class;
    }
}
