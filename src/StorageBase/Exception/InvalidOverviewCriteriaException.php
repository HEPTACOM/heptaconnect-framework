<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;
use RuntimeException;

class InvalidOverviewCriteriaException extends RuntimeException
{
    private OverviewCriteriaContract $criteria;

    public function __construct(OverviewCriteriaContract $criteria, int $code, ?\Throwable $throwable = null)
    {
        parent::__construct('Overview criteria cannot be processed as it contains invalid values', $code, $throwable);
        $this->criteria = $criteria;
    }

    public function getCriteria(): OverviewCriteriaContract
    {
        return $this->criteria;
    }
}
