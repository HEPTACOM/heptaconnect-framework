<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity;

abstract class DatasetEntityTracker
{
    private static array $contextStack = [];

    public static function report(DatasetEntity $entity): void
    {
        if (empty(self::$contextStack)) {
            return;
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
}
