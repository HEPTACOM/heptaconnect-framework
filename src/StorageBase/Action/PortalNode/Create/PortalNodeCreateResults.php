<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult>
 */
class PortalNodeCreateResults extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return PortalNodeCreateResult::class;
    }
}
