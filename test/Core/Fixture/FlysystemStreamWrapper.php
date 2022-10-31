<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Core\File\Filesystem\Contract\StreamWrapperInterface;

final class FlysystemStreamWrapper implements StreamWrapperInterface
{
    private TwistorFlysystemStreamWrapper $decorated;

    public function __construct()
    {
    }

    public function __destruct()
    {
        unset($this->decorated);
    }

    /**
     * Forwards the context resource
     */
    public function __get(string $name)
    {
        return $this->decorated->$name;
    }

    /**
     * Forwards the context resource
     */
    public function __set(string $name, $value): void
    {
        $this->decorated->$name = $value;
    }

    public function setDecorated(TwistorFlysystemStreamWrapper $decorated): void
    {
        $this->decorated = $decorated;
    }

    public function dir_closedir(): bool
    {
        return $this->decorated->dir_closedir();
    }

    public function dir_opendir(string $path, int $options): bool
    {
        return $this->decorated->dir_opendir($path, $options);
    }

    public function dir_readdir()
    {
        return $this->decorated->dir_readdir();
    }

    public function dir_rewinddir(): bool
    {
        return $this->decorated->dir_rewinddir();
    }

    public function mkdir(string $path, int $mode, int $options): bool
    {
        return $this->decorated->mkdir($path, $mode, $options);
    }

    public function rename(string $path_from, string $path_to): bool
    {
        return $this->decorated->rename($path_from, $path_to);
    }

    public function rmdir(string $path, int $options): bool
    {
        return $this->decorated->rmdir($path, $options);
    }

    public function stream_cast(int $cast_as)
    {
        return $this->decorated->stream_cast($cast_as);
    }

    public function stream_close(): void
    {
        $this->decorated->stream_close();
    }

    public function stream_eof(): bool
    {
        return $this->decorated->stream_eof();
    }

    public function stream_flush(): bool
    {
        return $this->decorated->stream_flush();
    }

    public function stream_lock(int $operation): bool
    {
        return $this->decorated->stream_lock($operation);
    }

    public function stream_metadata(string $path, int $option, $value): bool
    {
        return $this->decorated->stream_metadata($path, $option, $value);
    }

    public function stream_open(string $path, string $mode, int $options, ?string &$opened_path): bool
    {
        // what is opened_path?
        return $this->decorated->stream_open($path, $mode, $options, $opened_path);
    }

    public function stream_read(int $count)
    {
        return $this->decorated->stream_read($count);
    }

    public function stream_seek(int $offset, int $whence = \SEEK_SET): bool
    {
        return $this->decorated->stream_seek($offset, $whence);
    }

    public function stream_set_option(int $option, int $arg1, int $arg2): bool
    {
        return $this->decorated->stream_set_option($option, $arg1, $arg2);
    }

    public function stream_stat()
    {
        return $this->decorated->stream_stat();
    }

    public function stream_tell(): int
    {
        return $this->decorated->stream_tell();
    }

    public function stream_truncate(int $new_size): bool
    {
        return $this->decorated->stream_truncate($new_size);
    }

    public function stream_write(string $data): int
    {
        return $this->decorated->stream_write($data);
    }

    public function unlink(string $path): bool
    {
        return $this->decorated->unlink($path);
    }

    public function url_stat(string $path, int $flags)
    {
        return $this->decorated->url_stat($path, $flags);
    }
}
