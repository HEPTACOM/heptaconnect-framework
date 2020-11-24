<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity;

abstract class DatasetEntityTracker
{
    /**
     * @psalm-var array<class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>, bool>
     */
    private static array $deniedClasses = [];

    /**
     * @psalm-var array<array<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>>
     */
    private static array $contextStack = [];

    public static function report(DatasetEntity $entity): void
    {
        if (empty(self::$contextStack)) {
            return;
        }

        foreach (self::$deniedClasses as $deniedClass => $_) {
            if ($entity instanceof $deniedClass) {
                return;
            }
        }

        $key = \array_key_last(self::$contextStack);
        \array_push(self::$contextStack[$key], $entity);
    }

    public static function listen(): void
    {
        \array_push(self::$contextStack, []);
    }

    public static function retrieve(): TrackedEntityCollection
    {
        return new TrackedEntityCollection(\array_pop(self::$contextStack) ?? []);
    }

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $className
     */
    public static function allow(string $className): void
    {
        unset(self::$deniedClasses[$className]);
    }

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $className
     */
    public static function deny(string $className): void
    {
        self::$deniedClasses[$className] = true;
    }
}
