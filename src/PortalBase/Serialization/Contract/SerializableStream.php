<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Contract;

use Heptacom\HeptaConnect\Portal\Base\Serialization\Exception\StreamCopyException;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\StreamInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class SerializableStream implements StreamInterface
{
    private StreamInterface $stream;

    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    public function __toString()
    {
        return $this->stream->__toString();
    }

    /**
     * @throws StreamCopyException
     */
    public function copy(): StreamInterface
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        // COPY FOR EXTERNAL
        $oldInternal = $this->stream->detach();

        if ($oldInternal === null) {
            throw new StreamCopyException(1636887426);
        }

        $oldInternalPosition = \ftell($oldInternal);

        if ($oldInternalPosition === false) {
            throw new StreamCopyException(1636887427);
        }

        $oldInternalIsSeekable = \stream_get_meta_data($oldInternal)['seekable'];
        $newExternal = \fopen('php://temp', 'rb+');

        if ($newExternal === false) {
            throw new StreamCopyException(1636887428);
        }

        \stream_copy_to_stream($oldInternal, $newExternal);
        \rewind($newExternal);

        // RECOVER INTERNAL
        if ($oldInternalIsSeekable) {
            \fseek($oldInternal, $oldInternalPosition);
            $newInternal = $oldInternal;
        } else {
            \fclose($oldInternal);

            $newInternal = \fopen('php://temp', 'rb+');

            if ($newInternal === false) {
                throw new StreamCopyException(1636887429);
            }

            \stream_copy_to_stream($newExternal, $newInternal);
            \rewind($newInternal);
            \rewind($newExternal);
        }

        // SET STATES
        $this->stream = $streamFactory->createStreamFromResource($newInternal);

        return $streamFactory->createStreamFromResource($newExternal);
    }

    public function close(): void
    {
        $this->stream->close();
    }

    public function detach()
    {
        return $this->stream->detach();
    }

    public function getSize()
    {
        return $this->stream->getSize();
    }

    public function tell()
    {
        return $this->stream->tell();
    }

    public function eof()
    {
        return $this->stream->eof();
    }

    public function isSeekable()
    {
        return $this->stream->isSeekable();
    }

    public function seek($offset, $whence = \SEEK_SET): void
    {
        $this->stream->seek($offset, $whence);
    }

    public function rewind(): void
    {
        $this->stream->rewind();
    }

    public function isWritable()
    {
        return $this->stream->isWritable();
    }

    public function write($string)
    {
        return $this->stream->write($string);
    }

    public function isReadable()
    {
        return $this->stream->isReadable();
    }

    public function read($length)
    {
        return $this->stream->read($length);
    }

    public function getContents()
    {
        return $this->stream->getContents();
    }

    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }
}
