<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreateResult;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreateResult>
 */
class RouteCreateResults extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return RouteCreateResult::class;
    }
}
