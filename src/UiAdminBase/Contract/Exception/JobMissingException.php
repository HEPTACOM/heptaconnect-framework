<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;

final class JobMissingException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    public function __construct(
        private JobKeyInterface $job,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('The given job key does not exist', $code, $previous);
    }

    public function getJob(): JobKeyInterface
    {
        return $this->job;
    }
}
