<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

abstract class DatasetEntity implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        $vars = \get_object_vars($this);

        /**
         * @var string|int              $property
         * @var string|int|float|object $value
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
