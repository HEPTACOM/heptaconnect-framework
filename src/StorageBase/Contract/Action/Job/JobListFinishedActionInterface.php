<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Listing\JobListFinishedResult;

interface JobListFinishedActionInterface
{
    /**
     * List all finished jobs.
     *
     * @return iterable<JobListFinishedResult>
     */
    public function list(): iterable;
}
