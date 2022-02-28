<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete\JobDeleteCriteria;

interface JobDeleteActionInterface
{
    /**
     * Delete jobs and their payloads.
     */
    public function delete(JobDeleteCriteria $criteria): void;
}
