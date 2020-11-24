<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity;

class SerializationDatasetEntity extends DatasetEntity
{
    public string $publicString = 'public';

    public \DateTimeInterface $publicDateTime;

    public int $publicInt = 42;

    public float $publicFloat = 13.37;

    protected string $protectedString = 'protected';

    private string $privateString = 'private';

    protected function initialize(): void
    {
        parent::initialize();

        $this->publicDateTime = new \DateTime();
    }
}
