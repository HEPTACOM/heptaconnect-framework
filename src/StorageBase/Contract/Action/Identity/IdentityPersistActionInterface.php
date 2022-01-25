<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity;

use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayload;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityPersistActionInterface
{
    /**
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function persist(IdentityPersistPayload $payload): void;
}
