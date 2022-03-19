<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface JobGetActionInterface
{
    /**
     * Get jobs and their payloads from the storage.
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<JobGetResult>
     */
    public function get(JobGetCriteria $criteria): iterable;
}
