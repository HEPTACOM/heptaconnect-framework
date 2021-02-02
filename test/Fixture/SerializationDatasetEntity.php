<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

class SerializationDatasetEntity extends DatasetEntityContract
{
    public string $publicString = 'public';

    public \DateTimeInterface $publicDateTime;

    public int $publicInt = 42;

    public float $publicFloat = 13.37;

    protected string $protectedString = 'protected';

    private string $privateString = 'private';

    public function __construct()
    {
        parent::__construct();

        $this->publicDateTime = new \DateTime('2010-11-20T14:30:50.000Z', new \DateTimeZone('UTC'));
    }

    public function getPublicString(): string
    {
        return $this->publicString;
    }

    public function setPublicString(string $publicString): void
    {
        $this->publicString = $publicString;
    }

    public function getPublicDateTime(): \DateTimeInterface
    {
        return $this->publicDateTime;
    }

    public function setPublicDateTime(\DateTimeInterface $publicDateTime): void
    {
        $this->publicDateTime = $publicDateTime;
    }

    public function getPublicInt(): int
    {
        return $this->publicInt;
    }

    public function setPublicInt(int $publicInt): void
    {
        $this->publicInt = $publicInt;
    }

    public function getPublicFloat(): float
    {
        return $this->publicFloat;
    }

    public function setPublicFloat(float $publicFloat): void
    {
        $this->publicFloat = $publicFloat;
    }

    public function getProtectedString(): string
    {
        return $this->protectedString;
    }

    public function setProtectedString(string $protectedString): void
    {
        $this->protectedString = $protectedString;
    }

    public function getPrivateString(): string
    {
        return $this->privateString;
    }

    public function setPrivateString(string $privateString): void
    {
        $this->privateString = $privateString;
    }
}
