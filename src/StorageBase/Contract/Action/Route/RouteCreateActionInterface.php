<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface RouteCreateActionInterface
{
    /**
     * Create routes between two portal nodes for an entity type.
     *
     * @throws CreateException
     */
    public function create(RouteCreatePayloads $payloads): RouteCreateResults;
}
