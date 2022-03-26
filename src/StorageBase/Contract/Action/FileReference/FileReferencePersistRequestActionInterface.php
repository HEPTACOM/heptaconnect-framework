<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference;

use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestResult;

interface FileReferencePersistRequestActionInterface
{
    /**
     * Persist serialized PSR-7 requests.
     */
    public function persistRequest(FileReferencePersistRequestPayload $payload): FileReferencePersistRequestResult;
}
