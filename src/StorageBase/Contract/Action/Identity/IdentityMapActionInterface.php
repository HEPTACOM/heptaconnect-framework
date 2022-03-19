<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity;

use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityMapActionInterface
{
    /**
     * Match entities to their identities stored in the storage layer.
     * In case there are no known identities stored these have to be created.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function map(IdentityMapPayload $payload): IdentityMapResult;
}
