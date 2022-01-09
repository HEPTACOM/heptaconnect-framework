<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface as SymfonyDenormalizerInterface;

interface DenormalizerInterface extends SymfonyDenormalizerInterface
{
    public function getType(): string;
}
