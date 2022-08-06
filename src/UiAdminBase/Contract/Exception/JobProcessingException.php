<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobProcessingException extends \RuntimeException
{
    private JobKeyCollection $processedJobs;

    private JobKeyCollection $failedJobs;

    private JobKeyCollection $notProcessedJobs;

    public function __construct(
        JobKeyCollection $processedJobs,
        JobKeyCollection $failedJobs,
        JobKeyCollection $notProcessedJobs,
        int $code,
        ?\Throwable $previous = null
    ) {
        $this->processedJobs = $processedJobs;
        $this->failedJobs = $failedJobs;
        $this->notProcessedJobs = $notProcessedJobs;
        $message = 'Some jobs failed to be processed. %d were processed, %d failed in processing, %d were not tried to process';

        parent::__construct(\sprintf($message, $processedJobs->count(), $failedJobs->count(), $notProcessedJobs->count()), $code, $previous);
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
