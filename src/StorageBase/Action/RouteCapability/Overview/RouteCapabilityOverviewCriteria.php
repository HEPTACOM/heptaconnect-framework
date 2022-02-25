<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

final class RouteCapabilityOverviewCriteria extends OverviewCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public const FIELD_NAME = 'target';

    public const FIELD_CREATED = 'created';

    /**
     * @var array<string, string>
     */
    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
    }
}
