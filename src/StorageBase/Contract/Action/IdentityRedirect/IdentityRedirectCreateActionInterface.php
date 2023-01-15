<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityRedirect;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Create\IdentityRedirectCreateResultCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Create\IdentityRedirectCreatePayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityRedirectCreateActionInterface
{
    /**
     * Add identity redirects to the storage.
     * These identity are directional, therefore order of "source portal node" and "target portal node" have a distinctive impact.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function create(IdentityRedirectCreatePayloadCollection $payloads): IdentityRedirectCreateResultCollection;
}
