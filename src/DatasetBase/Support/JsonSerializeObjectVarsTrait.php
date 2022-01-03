<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

trait JsonSerializeObjectVarsTrait
{
    public function jsonSerialize(): array
    {
        $vars = \get_object_vars($this);

        /**
         * @var string|int                   $property
         * @var string|int|bool|float|object $value
         */
        foreach ($vars as $property => $value) {
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format(\DateTimeInterface::ATOM);
            }

            $vars[$property] = $value;
        }

        return $vars;
    }
}
