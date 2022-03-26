<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference;

use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestResult;

interface FileReferenceGetRequestActionInterface
{
    /**
     * Get serialized PSR-7 requests.
     *
     * @return iterable<FileReferenceGetRequestResult>
     */
    public function getRequest(FileReferenceGetRequestCriteria $criteria): iterable;
}
