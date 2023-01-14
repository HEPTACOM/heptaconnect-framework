<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityDirection;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Create\IdentityDirectionCreatePayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Create\IdentityDirectionCreateResultCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityDirectionCreateActionInterface
{
    /**
     * Add directional identities to the storage.
     * These identity are directional, therefore order of "source portal node" and "target portal node" have a distinctive impact.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function create(IdentityDirectionCreatePayloadCollection $payloads): IdentityDirectionCreateResultCollection;
}
