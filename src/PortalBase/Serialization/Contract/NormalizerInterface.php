<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizerInterface;

interface NormalizerInterface extends SymfonyNormalizerInterface
{
    /**
     * Returns the supported type to normalize.
     * Used for a selection by strategy pattern.
     */
    public function getType(): string;
}
