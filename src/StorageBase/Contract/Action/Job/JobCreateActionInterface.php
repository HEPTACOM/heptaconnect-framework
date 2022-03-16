<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface JobCreateActionInterface
{
    /**
     * Create jobs with their payloads.
     * The initial state must be open.
     *
     * @throws CreateException
     */
    public function create(JobCreatePayloads $payloads): JobCreateResults;
}
