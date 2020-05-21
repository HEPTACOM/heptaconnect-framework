<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\KeyInterface;

trait PrimaryKeyTrait
{
    protected KeyInterface $primaryKey;

    public function getPrimaryKey(): KeyInterface
    {
        return $this->primaryKey;
    }

    public function setPrimaryKey(KeyInterface $primaryKey): void
    {
        $this->primaryKey = $primaryKey;
    }
}
