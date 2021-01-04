<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

trait SetStateTrait
{
    public static function __set_state(array $an_array)
    {
        return static::createStaticFromArray($an_array);
    }

    private static function createStaticFromArray(array $an_array)
    {
        $result = new static();

        foreach ($an_array as $key => $value) {
            $setter = 'set'.\ucfirst($key);

            try {
                $method = new \ReflectionMethod($result, $setter);
            } catch (\Throwable $ignored) {
                continue;
            }

            if (!$method->isPublic()) {
                continue;
            }

            if ($method->isAbstract()) {
                continue;
            }

            if ($method->isStatic()) {
                continue;
            }

            $method->invoke($result, $value);
        }

        return $result;
    }
}
