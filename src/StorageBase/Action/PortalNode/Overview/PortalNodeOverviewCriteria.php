<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
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

    private ClassStringReferenceCollection $classNameFilter;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
        $this->classNameFilter = new ClassStringReferenceCollection();
    }

    public function getClassNameFilter(): ClassStringReferenceCollection
    {
        return $this->classNameFilter;
    }

    public function setClassNameFilter(ClassStringReferenceCollection $classNameFilter): void
    {
        $this->classNameFilter = $classNameFilter;
    }
}
