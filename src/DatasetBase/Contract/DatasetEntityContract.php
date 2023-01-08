<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\DeferralAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\DependencyAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\PrimaryKeyTrait;
use Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait;

/**
 * @psalm-consistent-constructor
 */
abstract class DatasetEntityContract implements AttachableInterface, AttachmentAwareInterface, DeferralAwareInterface, PrimaryKeyAwareInterface, \JsonSerializable
{
    use AttachmentAwareTrait;
    use DeferralAwareTrait;
    use DependencyAwareTrait;
    use JsonSerializeObjectVarsTrait;
    use PrimaryKeyTrait;
    use SetStateTrait;

    public function __construct()
    {
    }

    public function setPrimaryKey(?string $primaryKey): void
    {
        if ($primaryKey !== $this->getPrimaryKey()) {
            $this->primaryKey = $primaryKey;

            /** @var ForeignKeyAwareInterface $aware */
            foreach ($this->getAttachments()->filter(
                fn ($o) => $o instanceof ForeignKeyAwareInterface && $o->getForeignEntityType()->equalsObjectType($this)
            ) as $aware) {
                if ($aware->getForeignKey() !== $primaryKey) {
                    $aware->setForeignKey($primaryKey);
                }
            }
        }
    }

    /**
     * Returns a class string instance for the type of the extending class.
     */
    final public static function class(): EntityType
    {
        return new EntityType(static::class);
    }
}
