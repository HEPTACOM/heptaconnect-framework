<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

class RouteCapabilityOverviewCriteria extends OverviewCriteriaContract
{
    public const FIELD_NAME = 'target';

    public const FIELD_CREATED = 'created';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];
}
