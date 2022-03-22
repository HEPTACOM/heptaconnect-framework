<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\File\Reference;

use Heptacom\HeptaConnect\Core\Bridge\File\FileContentsUrlProviderInterface;
use Heptacom\HeptaConnect\Core\Bridge\File\FileRequestUrlProviderInterface;
use Heptacom\HeptaConnect\Core\File\FileReferenceFactory;
use Heptacom\HeptaConnect\Core\File\FileReferenceResolver;
use Heptacom\HeptaConnect\Core\File\Reference\ContentsFileReference;
use Heptacom\HeptaConnect\Core\File\Reference\PublicUrlFileReference;
use Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedContentsFileReference;
use Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedPublicUrlFileReference;
use Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry;
use Heptacom\HeptaConnect\Core\Storage\RequestStorage;
use Heptacom\HeptaConnect\Core\Test\Fixture\DependentPortal;
use Heptacom\HeptaConnect\Core\Web\Http\HttpClient;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\DenormalizerInterface;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\NormalizerInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\File\FileReferenceFactory
 * @covers \Heptacom\HeptaConnect\Core\File\FileReferenceResolver
 * @covers \Heptacom\HeptaConnect\Core\File\Reference\ContentsFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\Reference\PublicUrlFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedContentsFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedPublicUrlFileReference
 * @covers \Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpClient
 * @covers \Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Support\DefaultRequestHeaders
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 */
class IntegrationTest extends TestCase
{
    public function testPublicUrl(): void
    {
        $normalizationRegistry = $this->createInMemoryNormalizationRegistry();
        $client = $this->createMock(ClientInterface::class);
        $fileContentsUrlProvider = $this->createMock(FileContentsUrlProviderInterface::class);
        $fileRequestUrlProvider = $this->createMock(FileRequestUrlProviderInterface::class);

        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            $streamFactory,
            $normalizationRegistry,
            $this->createMock(RequestStorage::class)
        );
        $resolver = new FileReferenceResolver(
            new HttpClient($client, Psr17FactoryDiscovery::findUriFactory()),
            Psr17FactoryDiscovery::findRequestFactory(),
            $portalNodeKey,
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry,
            $this->createMock(RequestStorage::class)
        );

        $body = $streamFactory->createStream('Flag');
        $body->seek(0);
        $client->expects(static::once())
            ->method('sendRequest')
            ->willReturn(Psr17FactoryDiscovery::findResponseFactory()->createResponse()->withBody($body));

        $reference = $factory->fromPublicUrl('https://heptaconnect.io/');

        static::assertInstanceOf(PublicUrlFileReference::class, $reference);
        static::assertSame('https://heptaconnect.io/', $reference->getPublicUrl());

        $resolvedReference = $resolver->resolve($reference);

        static::assertInstanceOf(ResolvedPublicUrlFileReference::class, $resolvedReference);
        static::assertSame('https://heptaconnect.io/', $resolvedReference->getPublicUrl());
        static::assertSame('Flag', $resolvedReference->getContents());
    }

    public function testPublicUrlFails(): void
    {
        $normalizationRegistry = $this->createInMemoryNormalizationRegistry();
        $client = $this->createMock(ClientInterface::class);
        $fileContentsUrlProvider = $this->createMock(FileContentsUrlProviderInterface::class);
        $fileRequestUrlProvider = $this->createMock(FileRequestUrlProviderInterface::class);

        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            Psr17FactoryDiscovery::findStreamFactory(),
            $normalizationRegistry,
            $this->createMock(RequestStorage::class)
        );
        $resolver = new FileReferenceResolver(
            (new HttpClient($client, Psr17FactoryDiscovery::findUriFactory()))->withExceptionTriggers(500),
            Psr17FactoryDiscovery::findRequestFactory(),
            $portalNodeKey,
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry
        );

        $client->expects(static::once())
            ->method('sendRequest')
            ->willReturn(Psr17FactoryDiscovery::findResponseFactory()->createResponse(500));

        $reference = $factory->fromPublicUrl('https://heptaconnect.io/');
        $resolvedReference = $resolver->resolve($reference);

        try {
            $resolvedReference->getContents();
        } catch (\Throwable $throwable) {
            // TODO
            static::fail('This exception needs to be typed!');
        }
    }

    public function testContentUrl(): void
    {
        $normalizationRegistry = $this->createInMemoryNormalizationRegistry();
        $fileContentsUrlProvider = $this->createMock(FileContentsUrlProviderInterface::class);
        $fileRequestUrlProvider = $this->createMock(FileRequestUrlProviderInterface::class);

        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            $streamFactory,
            $normalizationRegistry,
            $this->createMock(RequestStorage::class)
        );
        $resolver = new FileReferenceResolver(
            $this->createMock(HttpClientContract::class),
            Psr17FactoryDiscovery::findRequestFactory(),
            $portalNodeKey,
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry
        );

        $fileContentsUrlProvider->expects(static::once())
            ->method('resolve')
            ->willReturn(Psr17FactoryDiscovery::findUriFactory()->createUri('https://heptaconnect.io/'));

        $reference = $factory->fromContents('{"foo": "bar"}', 'text/json');

        static::assertInstanceOf(ContentsFileReference::class, $reference);
        static::assertSame('text/json', $reference->getMimeType());

        $resolvedReference = $resolver->resolve($reference);

        static::assertInstanceOf(ResolvedContentsFileReference::class, $resolvedReference);
        static::assertSame('https://heptaconnect.io/', $resolvedReference->getPublicUrl());
        static::assertSame('{"foo": "bar"}', $resolvedReference->getContents());
    }

    public function testContentUrlFails(): void
    {
        $normalizationRegistry = $this->createInMemoryNormalizationRegistry();
        $fileContentsUrlProvider = $this->createMock(FileContentsUrlProviderInterface::class);
        $fileRequestUrlProvider = $this->createMock(FileRequestUrlProviderInterface::class);

        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            Psr17FactoryDiscovery::findStreamFactory(),
            $normalizationRegistry,
            $this->createMock(RequestStorage::class)
        );
        $resolver = new FileReferenceResolver(
            $this->createMock(HttpClientContract::class),
            Psr17FactoryDiscovery::findRequestFactory(),
            $portalNodeKey,
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry
        );

        $reference = $factory->fromContents('{"foo": "bar"}', 'text/json');
        $resolvedReference = $resolver->resolve($reference);

        try {
            $resolvedReference->getContents();
        } catch (\Throwable $throwable) {
            // TODO
            static::fail('This exception needs to be typed!');
        }
    }

    private function createInMemoryNormalizationRegistry(): NormalizationRegistry
    {
        $array = new \ArrayObject();
        $normalizer = new class($array) implements NormalizerInterface {
            private \ArrayObject $array;

            public function __construct(\ArrayObject $array)
            {
                $this->array = $array;
            }

            public function supportsNormalization($data, ?string $format = null)
            {
                return true;
            }

            public function getType(): string
            {
                return 'array';
            }

            public function normalize($object, ?string $format = null, array $context = [])
            {
                $result = $this->array->count();
                $this->array[$result] = $object;

                return (string) $result;
            }
        };
        $denormalizer = new class($array) implements DenormalizerInterface {
            private \ArrayObject $array;

            public function __construct(\ArrayObject $array)
            {
                $this->array = $array;
            }

            public function getType(): string
            {
                return 'array';
            }

            public function denormalize($data, string $type, ?string $format = null, array $context = [])
            {
                return $this->array[(string) $data];
            }

            public function supportsDenormalization($data, string $type, ?string $format = null)
            {
                return $type === 'array';
            }
        };

        return new NormalizationRegistry([$normalizer], [$denormalizer]);
    }
}
