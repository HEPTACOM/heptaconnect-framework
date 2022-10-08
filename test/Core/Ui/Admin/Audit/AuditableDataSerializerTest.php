<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Audit;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditableDataSerializer;
use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditableDataSerializer
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait
 */
final class AuditableDataSerializerTest extends TestCase
{
    public function testSerializationOfAttachments(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $serializer = new AuditableDataSerializer($logger);

        $aware = new class() implements AttachmentAwareInterface, AuditableDataAwareInterface {
            use AttachmentAwareTrait;

            public function __construct()
            {
                $this->attachments = new AttachmentCollection();
            }

            public function getAuditableData(): array
            {
                return [];
            }
        };
        $aware->attach(new FooBarEntity());

        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::never())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $logger->expects(static::never())->method('notice');
        $logger->expects(static::never())->method('info');
        $logger->expects(static::never())->method('debug');
        $logger->expects(static::never())->method('log');

        $serialized = $serializer->serialize($aware);

        static::assertSame('{"data":[],"attachedTypes":["Heptacom\\\\HeptaConnect\\\\Core\\\\Test\\\\Fixture\\\\FooBarEntity"]}', $serialized);
    }

    public function testSerialization(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $serializer = new AuditableDataSerializer($logger);

        $aware = new class() implements AuditableDataAwareInterface {
            public function getAuditableData(): array
            {
                return [
                    'hello' => 'world',
                ];
            }
        };

        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::never())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $logger->expects(static::never())->method('notice');
        $logger->expects(static::never())->method('info');
        $logger->expects(static::never())->method('debug');
        $logger->expects(static::never())->method('log');

        $serialized = $serializer->serialize($aware);

        static::assertSame('{"data":{"hello":"world"}}', $serialized);
    }

    public function testErrorOnSerialization(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $serializer = new AuditableDataSerializer($logger);

        $aware = new class() implements AuditableDataAwareInterface, \JsonSerializable {
            public function getAuditableData(): array
            {
                return [
                    'hello' => $this,
                    'foo' => 'bar',
                ];
            }

            public function jsonSerialize()
            {
                return $this->getAuditableData();
            }
        };

        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::once())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $logger->expects(static::never())->method('notice');
        $logger->expects(static::never())->method('info');
        $logger->expects(static::never())->method('debug');
        $logger->expects(static::never())->method('log');

        $serialized = $serializer->serialize($aware);

        static::assertSame('{"data":{"hello":{"hello":null,"foo":"bar"},"foo":"bar"}}', $serialized);
    }

    public function testExceptionDuringSerialization(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $serializer = new AuditableDataSerializer($logger);

        $aware = new class() implements AuditableDataAwareInterface, \JsonSerializable {
            public function getAuditableData(): array
            {
                return [
                    'hello' => $this,
                    'foo' => 'bar',
                ];
            }

            public function jsonSerialize()
            {
                throw new \RuntimeException();
            }
        };

        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::once())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $logger->expects(static::never())->method('notice');
        $logger->expects(static::never())->method('info');
        $logger->expects(static::never())->method('debug');
        $logger->expects(static::never())->method('log');

        $serialized = $serializer->serialize($aware);

        static::assertSame('{"$error":"An unrecoverable error happened during serialization"}', $serialized);
    }

    public function testExceptionDuringFetchingAuditableData(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $serializer = new AuditableDataSerializer($logger);

        $aware = new class() implements AuditableDataAwareInterface {
            public function getAuditableData(): array
            {
                throw new \RuntimeException();
            }
        };

        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::once())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $logger->expects(static::never())->method('notice');
        $logger->expects(static::never())->method('info');
        $logger->expects(static::never())->method('debug');
        $logger->expects(static::never())->method('log');

        $serialized = $serializer->serialize($aware);

        static::assertSame('{"data":[]}', $serialized);
    }
}
