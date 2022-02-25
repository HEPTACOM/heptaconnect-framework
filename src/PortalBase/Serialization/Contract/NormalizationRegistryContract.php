<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

/**
 * Central service provider to look for serialisation services for various types.
 * These services can be used to prepare data for different storages and read them again.
 * It is suggested to store the normalization type for later.
 */
abstract class NormalizationRegistryContract
{
    /**
     * Returns a suitable normalizer for the given data, otherwise null.
     *
     * @param mixed $value
     */
    public function getNormalizer($value): ?NormalizerInterface
    {
        return null;
    }

    /**
     * Returns a normalizer for the given supported type, when the type is not supported it returns null.
     */
    public function getNormalizerByType(string $type): ?NormalizerInterface
    {
        return null;
    }

    /**
     * Returns a denormalizer for the given supported type, when the type is not supported it returns null.
     */
    public function getDenormalizer(string $type): ?DenormalizerInterface
    {
        return null;
    }
}
