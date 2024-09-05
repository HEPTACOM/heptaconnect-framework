<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<FirstEntity>
 */
final class FirstEntityCollectionWithOtherProperties extends AbstractObjectCollection
{
    private ?SecondEntity $secondEntity = null;

    public function getSecondEntity(): ?SecondEntity
    {
        return $this->secondEntity;
    }

    public function setSecondEntity(?SecondEntity $secondEntity): void
    {
        $this->secondEntity = $secondEntity;
    }

    protected function getT(): string
    {
        return FirstEntity::class;
    }
}
