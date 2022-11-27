<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityErrorCreateActionInterface
{
    /**
     * Create identity errors with their payloads.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function create(IdentityErrorCreatePayloads $payloads): IdentityErrorCreateResults;
}
