<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobProcessingException extends \RuntimeException
{
    public function __construct(
        private JobKeyCollection $processedJobs,
        private JobKeyCollection $failedJobs,
        private JobKeyCollection $notProcessedJobs,
        int $code,
        ?\Throwable $previous = null
    ) {
        $message = 'Some jobs failed to be processed. %d were processed, %d failed in processing, %d were not tried to process';

        parent::__construct(\sprintf($message, $this->processedJobs->count(), $this->failedJobs->count(), $this->notProcessedJobs->count()), $code, $previous);
    }

    public function getProcessedJobs(): JobKeyCollection
    {
        return $this->processedJobs;
    }

    public function getFailedJobs(): JobKeyCollection
    {
        return $this->failedJobs;
    }

    public function getNotProcessedJobs(): JobKeyCollection
    {
        return $this->notProcessedJobs;
    }
}
