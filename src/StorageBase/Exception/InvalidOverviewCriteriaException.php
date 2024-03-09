<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

class InvalidOverviewCriteriaException extends \RuntimeException
{
    public function __construct(
        private OverviewCriteriaContract $criteria,
        int $code,
        ?\Throwable $throwable = null
    ) {
        parent::__construct('Overview criteria cannot be processed as it contains invalid values', $code, $throwable);
    }

    public function getCriteria(): OverviewCriteriaContract
    {
        return $this->criteria;
    }
}
