<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

/**
 * Identifies a storage key for serialized PSR-7 requests of file references.
 */
interface FileReferenceRequestKeyInterface extends StorageKeyInterface
{
}
