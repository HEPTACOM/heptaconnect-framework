<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Create;

use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface JobCreateActionInterface
{
    /**
     * @throws CreateException
     */
    public function create(JobCreatePayloads $payloads): JobCreateResults;
}
