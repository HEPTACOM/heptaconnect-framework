<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface ForeignKeyAwareInterface
{
    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getForeignDatasetEntityClassName(): string;

    public function getForeignKey(): ?string;

    public function setForeignKey(?string $foreignKey): void;
}
