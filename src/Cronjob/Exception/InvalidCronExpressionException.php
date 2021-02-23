<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Exception;

use Throwable;

class InvalidCronExpressionException extends \RuntimeException
{
    private string $cronExpression;

    public function __construct(string $cronExpression, ?Throwable $previous = null)
    {
        parent::__construct(\sprintf('CronExpression "%s" is invalid', $cronExpression), 0, $previous);
        $this->cronExpression = $cronExpression;
    }

    public function getCronExpression(): string
    {
        return $this->cronExpression;
    }
}
