<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

abstract class NormalizationRegistryContract
{
    public function getNormalizer($value): ?NormalizerInterface
    {
        return null;
    }

    public function getNormalizerByType(string $type): ?NormalizerInterface
    {
        return null;
    }

    public function getDenormalizer(string $type): ?DenormalizerInterface
    {
        return null;
    }
}
