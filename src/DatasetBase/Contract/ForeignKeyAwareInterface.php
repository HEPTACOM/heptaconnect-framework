<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface ForeignKeyAwareInterface
{
    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getForeignEntityType(): string;

    public function getForeignKey(): ?string;

    public function setForeignKey(?string $foreignKey): void;
}
