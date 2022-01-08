<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult>
 */
class RouteCreateResults extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return RouteCreateResult::class;
    }
}
