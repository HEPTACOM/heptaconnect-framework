<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

class PortalNodeOverviewCriteria extends OverviewCriteriaContract
{
    public const FIELD_CLASS_NAME = 'className';

    public const FIELD_CREATED = 'created';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];

    /**
     * @var array<class-string<PortalContract>>
     */
    protected array $classNameFilter = [];

    /**
     * @return array<class-string<PortalContract>>
     */
    public function getClassNameFilter(): array
    {
        return $this->classNameFilter;
    }

    /**
     * @param array<class-string<PortalContract>> $classNameFilter
     */
    public function setClassNameFilter(array $classNameFilter): void
    {
        $this->classNameFilter = $classNameFilter;
    }
}
