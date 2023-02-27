<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class JobScheduleCriteria implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    private JobKeyCollection $jobKeys;

    public function __construct()
    {
        $this->jobKeys = new JobKeyCollection();
    }

    public function getJobKeys(): JobKeyCollection
    {
        return $this->jobKeys;
    }

    public function setJobKeys(JobKeyCollection $jobKeys): void
    {
        $this->jobKeys = $jobKeys;
    }

    public function getAuditableData(): array
    {
        return [
            'jobKeys' => $this->getJobKeys(),
        ];
    }
}
