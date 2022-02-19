<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity;

use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect\IdentityReflectPayload;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsharableOwnerException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface IdentityReflectActionInterface
{
    /**
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     * @throws UnsharableOwnerException
     */
    public function reflect(IdentityReflectPayload $payload): void;
}
