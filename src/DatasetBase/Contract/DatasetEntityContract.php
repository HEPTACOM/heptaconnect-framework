<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\DependencyCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\DeferralAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\DependencyAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\PrimaryKeyTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait;

abstract class DatasetEntityContract implements AttachableInterface, DeferralAwareInterface, PrimaryKeyAwareInterface, \JsonSerializable
{
    use AttachmentAwareTrait;
    use DeferralAwareTrait;
    use DependencyAwareTrait;
    use JsonSerializeObjectVarsTrait;
    use PrimaryKeyTrait;
    use SetStateTrait;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
        $this->dependencies = new DependencyCollection();
    }

    public function setPrimaryKey(?string $primaryKey): void
    {
        if ($primaryKey !== $this->getPrimaryKey()) {
            $this->primaryKey = $primaryKey;

            /** @var ForeignKeyAwareInterface $aware */
            foreach ($this->getAttachments()->filter(
                static fn ($o) => $o instanceof ForeignKeyAwareInterface && $o->getForeignEntityType() === static::class
            ) as $aware) {
                if ($aware->getForeignKey() !== $primaryKey) {
                    $aware->setForeignKey($primaryKey);
                }
            }
        }
    }
}
