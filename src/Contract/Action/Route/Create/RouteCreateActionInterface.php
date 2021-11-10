<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create;

use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface RouteCreateActionInterface
{
    /**
     * @throws CreateException
     */
    public function create(RouteCreatePayloads $payloads): RouteCreateResults;
}
