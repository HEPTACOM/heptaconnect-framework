<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityDirection;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Delete\IdentityDirectionDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Exception\DeleteException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityDirectionDeleteActionInterface
{
    /**
     * Removes directional identities from the storage.
     *
     * @throws DeleteException
     * @throws UnsupportedStorageKeyException
     */
    public function delete(IdentityDirectionDeleteCriteria $criteria): void;
}
