<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

/**
 * @psalm-consistent-constructor
 */
trait SetStateTrait
{
    /**
     * @return static
     */
    public static function __set_state(array $an_array)
    {
        return static::createStaticFromArray($an_array);
    }

    /**
     * @return static
     */
    private static function createStaticFromArray(array $an_array)
    {
        $result = new static();

        /** @var mixed $value */
        foreach ($an_array as $key => $value) {
            if (\is_numeric($key)) {
                continue;
            }

            $setter = 'set'.\ucfirst($key);

            try {
                $method = new \ReflectionMethod($result, $setter);
            } catch (\Throwable $ignored) {
                continue;
            }

            if (\is_null($value)) {
                $firstParameter = $method->getParameters()[0] ?? null;

                if ($firstParameter instanceof \ReflectionParameter && !$firstParameter->allowsNull()) {
                    continue;
                }
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
