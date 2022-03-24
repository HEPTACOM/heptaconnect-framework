<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Overview;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

class PortalNodeAliasOverviewCriteria extends OverviewCriteriaContract
{
    public const FIELD_ALIAS = 'alias';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_ALIAS => self::SORT_ASC,
    ];
}
