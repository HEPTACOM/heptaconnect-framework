<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Listing;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Listing\JobListFinishedResult;

interface JobListFinishedActionInterface
{
    /**
     * @return iterable<JobListFinishedResult>
     */
    public function list(): iterable;
}
