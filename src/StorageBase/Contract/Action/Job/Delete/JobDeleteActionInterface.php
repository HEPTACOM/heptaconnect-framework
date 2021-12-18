<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Delete;

interface JobDeleteActionInterface
{
    public function delete(JobDeleteCriteria $criteria): void;
}
