<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobsMissingException extends \UnexpectedValueException implements InvalidArgumentThrowableInterface
{
    public function __construct(
        private JobKeyCollection $jobs,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('The given job keys do not exist', $code, $previous);
    }

    public function getJobs(): JobKeyCollection
    {
        return $this->jobs;
    }
}
