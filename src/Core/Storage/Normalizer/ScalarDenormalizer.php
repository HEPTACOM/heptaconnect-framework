<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Storage\Normalizer;

use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\DenormalizerInterface;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Exception\InvalidArgumentException;

final class ScalarDenormalizer implements DenormalizerInterface
{
    /**
     * @psalm-return 'scalar'
     */
    public function getType(): string
    {
        return 'scalar';
    }

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (!$this->supportsDenormalization($data, $type)) {
            throw new InvalidArgumentException();
        }

        return \unserialize($data, ['allowed_classes' => false]);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === $this->getType()
            && \is_string($data)
            && (\unserialize($data, ['allowed_classes' => false]) !== false || $data === 'b:0;');
    }
}
