<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizerInterface;

interface NormalizerInterface extends SymfonyNormalizerInterface
{
    public function getType(): string;
}
