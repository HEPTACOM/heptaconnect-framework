<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

final class PortalNodeOverviewCriteria extends OverviewCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

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
