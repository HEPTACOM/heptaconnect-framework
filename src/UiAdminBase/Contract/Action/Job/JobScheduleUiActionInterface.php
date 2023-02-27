<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobsMissingException;

interface JobScheduleUiActionInterface extends UiActionInterface
{
    /**
     * Prepares the job states and sends the jobs on the message queue.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws JobsMissingException
     */
    public function schedule(JobScheduleCriteria $criteria, UiActionContextInterface $context): JobScheduleResult;
}
