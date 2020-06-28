<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

abstract class DatasetEntity implements Contract\DatasetEntityInterface
{
    use Support\AttachmentAwareTrait;
    use Support\DependencyAwareTrait;
    use Support\JsonSerializeObjectVarsTrait;
    use Support\PrimaryKeyTrait;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
        $this->dependencies = new DependencyCollection();
    }
}
