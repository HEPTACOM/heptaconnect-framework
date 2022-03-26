<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\File\Reference;

use Heptacom\HeptaConnect\Core\Bridge\File\FileContentsUrlProviderInterface;
use Heptacom\HeptaConnect\Core\Bridge\File\FileRequestUrlProviderInterface;
use Heptacom\HeptaConnect\Core\File\FileReferenceFactory;
use Heptacom\HeptaConnect\Core\File\FileReferenceResolver;
use Heptacom\HeptaConnect\Core\File\Reference\ContentsFileReference;
use Heptacom\HeptaConnect\Core\File\Reference\PublicUrlFileReference;
use Heptacom\HeptaConnect\Core\File\Reference\RequestFileReference;
use Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedContentsFileReference;
use Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedPublicUrlFileReference;
use Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedRequestFileReference;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Storage\Contract\RequestStorageContract;
use Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestDenormalizer;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestNormalizer;
use Heptacom\HeptaConnect\Core\Storage\RequestStorage;
use Heptacom\HeptaConnect\Core\Test\Fixture\DependentPortal;
use Heptacom\HeptaConnect\Core\Web\Http\HttpClient;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\DenormalizerInterface;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\NormalizerInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestResult;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference\FileReferenceGetRequestActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference\FileReferencePersistRequestActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\FileReferenceRequestKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\File\FileReferenceFactory
 * @covers \Heptacom\HeptaConnect\Core\File\FileReferenceResolver
 * @covers \Heptacom\HeptaConnect\Core\File\Reference\ContentsFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\Reference\PublicUrlFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\Reference\RequestFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedContentsFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedPublicUrlFileReference
 * @covers \Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedRequestFileReference
 * @covers \Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry
 * @covers \Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestDenormalizer
 * @covers \Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestNormalizer
 * @covers \Heptacom\HeptaConnect\Core\Storage\RequestStorage
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpClient
 * @covers \Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Exception\HttpException
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Support\DefaultRequestHeaders
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet\FileReferenceGetRequestResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist\FileReferencePersistRequestResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\FileReferenceRequestKeyCollection
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
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            $streamFactory,
            $normalizationRegistry,
            $this->createMock(RequestStorageContract::class)
        );
        $resolver = new FileReferenceResolver(
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry,
            $this->createMock(RequestStorageContract::class),
            $portalStackServiceContainerFactory
        );

        $body = $streamFactory->createStream('Flag');
        $body->seek(0);
        $client->expects(static::once())
            ->method('sendRequest')
            ->willReturn(Psr17FactoryDiscovery::findResponseFactory()->createResponse()->withBody($body));

        $portalStackServiceContainerFactory->expects(static::atLeastOnce())
            ->method('create')
            ->with($portalNodeKey)
            ->willReturn($this->getInMemoryContainer([
                HttpClientContract::class => new HttpClient($client, Psr17FactoryDiscovery::findUriFactory()),
                ClientInterface::class => $client,
            ]));

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
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);

        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            Psr17FactoryDiscovery::findStreamFactory(),
            $normalizationRegistry,
            $this->createMock(RequestStorageContract::class)
        );
        $resolver = new FileReferenceResolver(
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry,
            $this->createMock(RequestStorageContract::class),
            $portalStackServiceContainerFactory
        );

        $client->expects(static::once())
            ->method('sendRequest')
            ->willReturn(Psr17FactoryDiscovery::findResponseFactory()->createResponse(500));

        $portalStackServiceContainerFactory->expects(static::atLeastOnce())
            ->method('create')
            ->with($portalNodeKey)
            ->willReturn($this->getInMemoryContainer([
                HttpClientContract::class => (new HttpClient($client, Psr17FactoryDiscovery::findUriFactory()))->withExceptionTriggers(500),
                ClientInterface::class => $client,
            ]));

        $reference = $factory->fromPublicUrl('https://heptaconnect.io/');
        $resolvedReference = $resolver->resolve($reference);

        static::expectException(\Throwable::class);

        $resolvedReference->getContents();
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
            $this->createMock(RequestStorageContract::class)
        );
        $resolver = new FileReferenceResolver(
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry,
            $this->createMock(RequestStorageContract::class),
            $this->createMock(PortalStackServiceContainerFactory::class)
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
        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);

        $resolvedReference = new ResolvedContentsFileReference(
            $portalNodeKey,
            '0',
            'text/json',
            $normalizationRegistry->getDenormalizer('array'),
            $fileContentsUrlProvider
        );

        static::expectException(\Throwable::class);

        $resolvedReference->getContents();
    }

    public function testStoredRequest(): void
    {
        $normalizationRegistry = $this->createInMemoryNormalizationRegistry();
        $fileContentsUrlProvider = $this->createMock(FileContentsUrlProviderInterface::class);
        $fileRequestUrlProvider = $this->createMock(FileRequestUrlProviderInterface::class);
        $client = $this->createMock(ClientInterface::class);
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $requestStorage = $this->createInMemoryRequestStorage();

        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            $streamFactory,
            $normalizationRegistry,
            $requestStorage,
        );
        $resolver = new FileReferenceResolver(
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry,
            $requestStorage,
            $portalStackServiceContainerFactory,
        );

        $fileRequestUrlProvider->expects(static::once())
            ->method('resolve')
            ->willReturn(Psr17FactoryDiscovery::findUriFactory()->createUri('https://heptaconnect.io/'));

        $portalStackServiceContainerFactory->expects(static::atLeastOnce())
            ->method('create')
            ->with($portalNodeKey)
            ->willReturn($this->getInMemoryContainer([
                HttpClientContract::class => new HttpClient($client, Psr17FactoryDiscovery::findUriFactory()),
                ClientInterface::class => $client,
            ]));

        $request = $requestFactory->createRequest('PATCH', 'https://heptaconnect.io/HEPTAconnect.pdf')
            ->withHeader('If-None-Match', 'b0933ba3-2f40-453d-ac9c-447492fbd48c')
            ->withHeader('Content-Type', 'application/pdf')
            ->withProtocolVersion('2.0')
            ->withBody($streamFactory->createStream('I could be a PDF'));

        $body = $streamFactory->createStream('I am a PDF');
        $body->seek(0);
        $client->expects(static::once())
            ->method('sendRequest')
            ->willReturnCallback(static function (RequestInterface $in) use ($request, $body) {
                static::assertSame($request->getMethod(), $in->getMethod());
                static::assertSame((string) $request->getBody(), (string) $in->getBody());
                static::assertSame($request->getHeaders(), $in->getHeaders());
                static::assertSame($request->getProtocolVersion(), $in->getProtocolVersion());
                static::assertSame($request->getRequestTarget(), $in->getRequestTarget());
                static::assertSame((string) $request->getUri(), (string) $in->getUri());

                return Psr17FactoryDiscovery::findResponseFactory()->createResponse()->withBody($body);
            });

        $reference = $factory->fromRequest($request);

        static::assertInstanceOf(RequestFileReference::class, $reference);
        static::assertTrue($reference->getPortalNodeKey()->equals($portalNodeKey));

        $resolvedReference = $resolver->resolve($reference);

        static::assertInstanceOf(ResolvedRequestFileReference::class, $resolvedReference);
        static::assertSame('https://heptaconnect.io/', $resolvedReference->getPublicUrl());
        static::assertSame('I am a PDF', $resolvedReference->getContents());
    }

    public function testStoredRequestFails(): void
    {
        $normalizationRegistry = $this->createInMemoryNormalizationRegistry();
        $fileContentsUrlProvider = $this->createMock(FileContentsUrlProviderInterface::class);
        $fileRequestUrlProvider = $this->createMock(FileRequestUrlProviderInterface::class);
        $client = $this->createMock(ClientInterface::class);
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $requestStorage = $this->createInMemoryRequestStorage();

        $portalNodeKey = new PreviewPortalNodeKey(DependentPortal::class);
        $factory = new FileReferenceFactory(
            $portalNodeKey,
            Psr17FactoryDiscovery::findStreamFactory(),
            $normalizationRegistry,
            $requestStorage,
        );
        $resolver = new FileReferenceResolver(
            $fileContentsUrlProvider,
            $fileRequestUrlProvider,
            $normalizationRegistry,
            $requestStorage,
            $portalStackServiceContainerFactory,
        );
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();

        $portalStackServiceContainerFactory->expects(static::atLeastOnce())
            ->method('create')
            ->with($portalNodeKey)
            ->willReturn($this->getInMemoryContainer([
                HttpClientContract::class => (new HttpClient($client, Psr17FactoryDiscovery::findUriFactory()))->withExceptionTriggers(500),
                ClientInterface::class => $client,
            ]));

        $client->expects(static::once())
            ->method('sendRequest')
            ->willReturn(Psr17FactoryDiscovery::findResponseFactory()->createResponse(500));

        $request = $requestFactory->createRequest('PATCH', 'https://heptaconnect.io/HEPTAconnect.pdf')
            ->withHeader('If-None-Match', 'b0933ba3-2f40-453d-ac9c-447492fbd48c')
            ->withHeader('Content-Type', 'application/pdf')
            ->withProtocolVersion('2.0')
            ->withBody($streamFactory->createStream('I could be a PDF'));

        $reference = $factory->fromRequest($request);
        $resolvedReference = $resolver->resolve($reference);

        static::expectException(\Throwable::class);

        $resolvedReference->getContents();
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

            public function supportsNormalization($data, $format = null)
            {
                return true;
            }

            public function getType(): string
            {
                return 'array';
            }

            public function normalize($object, $format = null, array $context = [])
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

            public function denormalize($data, $type, $format = null, array $context = [])
            {
                $index = (string) $data;

                if (!isset($this->array[$index])) {
                    throw new \InvalidArgumentException('Index not found');
                }

                return $this->array[$index];
            }

            public function supportsDenormalization($data, $type, $format = null)
            {
                return $type === 'array';
            }
        };

        return new NormalizationRegistry([$normalizer], [$denormalizer]);
    }

    private function getInMemoryContainer(array $services): ContainerInterface
    {
        return new class($services) implements ContainerInterface {
            private array $services;

            public function __construct(array $services)
            {
                $this->services = $services;
            }

            public function get($id)
            {
                return $this->services[$id] ?? null;
            }

            public function has($id)
            {
                return ($this->services[$id] ?? null) !== null;
            }
        };
    }

    private function createInMemoryRequestStorage(): RequestStorageContract
    {
        $getRequestAction = $this->createMock(FileReferenceGetRequestActionInterface::class);
        $persistRequestAction = $this->createMock(FileReferencePersistRequestActionInterface::class);
        $storageKeyGenerator = new class() extends StorageKeyGeneratorContract {
            public function generateKeys(string $keyClassName, int $count): iterable
            {
                return [];
            }
        };

        $getRequestAction->method('getRequest')
            ->willReturnCallback(static function (FileReferenceGetRequestCriteria $c) {
                foreach ($c->getFileReferenceRequestKeys() as $key) {
                    yield new FileReferenceGetRequestResult($c->getPortalNodeKey(), $key, $key->jsonSerialize());
                }
            });

        $persistRequestAction->method('persistRequest')
            ->willReturnCallback(static function (FileReferencePersistRequestPayload $p) {
                $result = new FileReferencePersistRequestResult($p->getPortalNodeKey());

                foreach ($p->getSerializedRequests() as $key => $serializedRequest) {
                    $result->addFileReferenceRequestKey($key, new class($serializedRequest) implements FileReferenceRequestKeyInterface {
                        private string $content;

                        public function __construct(string $content)
                        {
                            $this->content = $content;
                        }

                        public function equals(StorageKeyInterface $other): bool
                        {
                            return \json_encode($other) === \json_encode($this);
                        }

                        public function jsonSerialize()
                        {
                            return $this->content;
                        }
                    });
                }

                return $result;
            });

        return new RequestStorage(
            new Psr7RequestNormalizer(),
            new Psr7RequestDenormalizer(),
            $getRequestAction,
            $persistRequestAction,
            $storageKeyGenerator
        );
    }
}
