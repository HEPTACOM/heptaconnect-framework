<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity;
use Heptacom\HeptaConnect\Dataset\Base\DependencyCollection;

class SerializationDatasetEntity extends DatasetEntity
{
    public string $publicString = 'public';

    public \DateTimeInterface $publicDateTime;

    public int $publicInt = 42;

    public float $publicFloat = 13.37;

    protected string $protectedString = 'protected';

    private string $privateString = 'private';

    public function __construct()
    {
        $this->publicDateTime = new \DateTime();
        $this->dependencies = new DependencyCollection();
    }
}
