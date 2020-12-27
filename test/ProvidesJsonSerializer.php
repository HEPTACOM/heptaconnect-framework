<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

trait ProvidesJsonSerializer
{
    /**
     * @throws \JsonException
     */
    protected function jsonEncodeAndDecode(\JsonSerializable $serializable): array
    {
        $encoded = \json_encode($serializable, \JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $decoded */
        $decoded = \json_decode($encoded, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
