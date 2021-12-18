<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Get;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface JobGetActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<JobGetResult>
     */
    public function get(JobGetCriteria $criteria): iterable;
}
