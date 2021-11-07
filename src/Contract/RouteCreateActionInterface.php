<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteCreateActionInterface
{
    /**
     * @return iterable<RouteCreateResult>
     *
     * @throws UnsupportedStorageKeyException
     *
     * @TODO add exception for creation failures
     */
    public function create(RouteCreatePayloads $params): iterable;
}
