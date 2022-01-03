<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Delete;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete\JobDeleteCriteria;

interface JobDeleteActionInterface
{
    public function delete(JobDeleteCriteria $criteria): void;
}
