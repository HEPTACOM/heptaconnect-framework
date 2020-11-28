<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity;

class DatasetEntityTracker
{
    /**
     * @psalm-var array<class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>, bool>
     */
    private array $deniedClasses = [];

    /**
     * @psalm-var array<array<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>>
     */
    private array $contextStack = [];

    public function report(DatasetEntity $entity): void
    {
        if (\count($this->contextStack) === 0) {
            return;
        }

        foreach ($this->deniedClasses as $deniedClass => $_) {
            if ($entity instanceof $deniedClass) {
                return;
            }
        }

        /** @var string|int|null $key */
        $key = \array_key_last($this->contextStack);

        if (\is_null($key)) {
            return;
        }

        $this->contextStack[$key][] = $entity;
    }

    public function listen(): void
    {
        $this->contextStack[] = [];
    }

    public function retrieve(): TrackedEntityCollection
    {
        return new TrackedEntityCollection(\array_pop($this->contextStack) ?? []);
    }

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $className
     */
    public function allow(string $className): void
    {
        unset($this->deniedClasses[$className]);
    }

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $className
     */
    public function deny(string $className): void
    {
        $this->deniedClasses[$className] = true;
    }

    public static function instance(): DatasetEntityTracker
    {
        static $instance = null;

        if (!$instance) {
            $instance = new DatasetEntityTracker();
        }

        return $instance;
    }
}
