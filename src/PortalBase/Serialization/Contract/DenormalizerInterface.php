<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface as SymfonyDenormalizerInterface;

interface DenormalizerInterface extends SymfonyDenormalizerInterface
{
    /**
     * Returns the supported type to normalize.
     * Used for a selection by strategy pattern.
     */
    public function getType(): string;
}
