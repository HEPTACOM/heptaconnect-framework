<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\DatasetEntityTracker;

abstract class DatasetEntity implements Contract\DatasetEntityInterface
{
    use Support\AttachmentAwareTrait;
    use Support\DependencyAwareTrait;
    use Support\JsonSerializeObjectVarsTrait;
    use Support\PrimaryKeyTrait;
    use Support\SetStateTrait;

    final public function __construct()
    {
        DatasetEntityTracker::instance()->report($this);

        $this->attachments = new AttachmentCollection();
        $this->dependencies = new DependencyCollection();

        $this->initialize();
    }

    final public function __clone()
    {
        DatasetEntityTracker::instance()->report($this);
    }

    final public function __wakeup()
    {
        DatasetEntityTracker::instance()->report($this);
    }

    public function setPrimaryKey(?string $primaryKey): void
    {
        if ($primaryKey !== $this->getPrimaryKey()) {
            $this->primaryKey = $primaryKey;

            /** @var ForeignKeyAwareInterface $aware */
            foreach ($this->getAttachments()->filter(
                static fn ($o) => $o instanceof ForeignKeyAwareInterface && $o->getForeignDatasetEntityClassName() === static::class
            ) as $aware) {
                if ($aware->getForeignKey() !== $primaryKey) {
                    $aware->setForeignKey($primaryKey);
                }
            }
        }
    }

    protected function initialize(): void
    {
    }
}
