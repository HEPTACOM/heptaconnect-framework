<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract;

final class RouteOverviewCriteria extends OverviewCriteriaContract implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public const FIELD_TARGET = 'target';

    public const FIELD_SOURCE = 'source';

    public const FIELD_ENTITY_TYPE = 'entityType';

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
