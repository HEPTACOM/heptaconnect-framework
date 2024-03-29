<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobRun\JobRunPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobProcessingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobsMissingException;

interface JobRunUiActionInterface extends UiActionInterface
{
    /**
     * Runs all jobs of the payload synchronously.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws JobsMissingException
     * @throws JobProcessingException
     */
    public function run(JobRunPayload $payload, UiActionContextInterface $context): void;
}
