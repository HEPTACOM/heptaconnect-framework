<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityRedirect;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Delete\IdentityRedirectDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Exception\DeleteException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityRedirectDeleteActionInterface
{
    /**
     * Removes identity redirects from the storage.
     *
     * @throws DeleteException
     * @throws UnsupportedStorageKeyException
     */
    public function delete(IdentityRedirectDeleteCriteria $criteria): void;
}
