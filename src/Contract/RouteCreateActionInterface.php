<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteCreateActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<RouteCreateResult>
     */
    public function create(RouteCreatePayloads $params): iterable;
}
