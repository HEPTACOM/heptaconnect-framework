<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\PrimaryKeyTrait;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\Json\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;

/**
 * @phpstan-consistent-constructor
 */
abstract class DatasetEntityContract implements AttachableInterface, AttachmentAwareInterface, PrimaryKeyAwareInterface, \JsonSerializable
{
    use AttachmentAwareTrait;
    use JsonSerializeObjectVarsTrait;
    use PrimaryKeyTrait;
    use SetStateTrait;

    public function __construct()
    {
    }

    #[\Override]
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
