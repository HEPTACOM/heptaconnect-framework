<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Storage;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Storage\Contract\StreamPathContract;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;

#[CoversClass(LogMessage::class)]
#[CoversClass(StreamPathContract::class)]
#[CoversClass(StreamNormalizer::class)]
#[CoversClass(SerializableStream::class)]
final class StreamNormalizerTest extends TestCase
{
    public function testLogging(): void
    {
        $logger = new class() extends AbstractLogger {
            private array $messages = [];

            private array $contexts = [];

            public function getMessages(): array
            {
                return $this->messages;
            }

            public function getContexts(): array
            {
                return $this->contexts;
            }

            public function log($level, $message, array $context = []): void
            {
                $this->messages[] = $message;
                $this->contexts[] = $context;
            }
        };

        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $stream = new SerializableStream($streamFactory->createStream(''));
        $norm = new StreamNormalizer(\sys_get_temp_dir(), new StreamPathContract(), $logger);

        static::assertTrue($norm->supportsNormalization($stream, $norm->getType()));
        $norm->normalize($stream, $norm->getType(), [
            'mediaId' => '2928b1b2191f4ad7905c3a893ca39aaa',
        ]);

        static::assertContains('2928b1b2191f4ad7905c3a893ca39aaa', $logger->getContexts()[0]);
        static::assertContains(LogMessage::STORAGE_STREAM_NORMALIZER_CONVERTS_HINT_TO_FILENAME(), $logger->getMessages());
    }
}
