<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\File\ResolvedReference;

use Heptacom\HeptaConnect\Core\Bridge\File\FileContentsUrlProviderInterface;
use Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\DenormalizerInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Http\Message\StreamInterface;

final class ResolvedContentsFileReference extends ResolvedFileReferenceContract
{
    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        private string $normalizedStream,
        private string $mimeType,
        private DenormalizerInterface $denormalizer,
        private FileContentsUrlProviderInterface $fileContentsUrlProvider
    ) {
        parent::__construct($portalNodeKey);
    }

    public function getPublicUrl(): string
    {
        return (string) $this->fileContentsUrlProvider->resolve(
            $this->getPortalNodeKey(),
            $this->normalizedStream,
            $this->mimeType
        );
    }

    public function getContents(): string
    {
        $stream = $this->denormalizer->denormalize($this->normalizedStream, $this->denormalizer->getType());

        if (!$stream instanceof StreamInterface) {
            throw new \UnexpectedValueException(
                'Denormalizing a normalized stream failed: ' . $this->normalizedStream,
                1647789503
            );
        }

        return (string) $stream;
    }
}
