<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;

/**
 * @psalm-consistent-constructor
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
trait SetStateTrait
{
    /**
     * @return static
     */
    public static function __set_state(array $an_array)
    {
        return self::createStaticFromArray($an_array);
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

            $setter = 'set' . \ucfirst($key);

            try {
                $method = new \ReflectionMethod($result, $setter);
            } catch (\ReflectionException) {
                $getter = 'get' . \ucfirst($key);

                try {
                    $method = new \ReflectionMethod($result, $getter);
                } catch (\Throwable) {
                    continue;
                }

                if (!self::isCallable($method)) {
                    continue;
                }

                /** @var mixed $initialValue */
                $initialValue = $method->invoke($result);

                if ($initialValue instanceof CollectionInterface && $value instanceof CollectionInterface) {
                    $initialValue->push($value);
                }
            } catch (\Throwable) {
                continue;
            }

            if ($value === null) {
                $firstParameter = $method->getParameters()[0] ?? null;

                if ($firstParameter instanceof \ReflectionParameter && !$firstParameter->allowsNull()) {
                    continue;
                }
            }

            if (!self::isCallable($method)) {
                continue;
            }

            $method->invoke($result, $value);
        }

        return $result;
    }

    private static function isCallable(\ReflectionMethod $method): bool
    {
        return $method->isPublic() && !$method->isAbstract() && !$method->isStatic();
    }
}
