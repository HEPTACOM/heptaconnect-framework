<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use League\Flysystem\FilesystemInterface;
use Twistor\FlysystemStreamWrapper;

final class TwistorFlysystemStreamWrapper extends FlysystemStreamWrapper
{
    private string $protocol = '';

    public function setFilesystem(FilesystemInterface $filesystem): void
    {
        $this->filesystem = $filesystem;
    }

    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function dir_readdir()
    {
        $result = parent::dir_readdir();

        if (\is_string($result)) {
            $result = $this->getProtocol() . '://' . $result;
        }

        return $result;
    }

    public static function registerFilesystem(FilesystemInterface $filesystem): string
    {
        $protocol = 'twistfly-' . (\count(FlysystemStreamWrapper::$filesystems) + 1);

        FlysystemStreamWrapper::$config[$protocol] = FlysystemStreamWrapper::$defaultConfiguration;
        FlysystemStreamWrapper::registerPlugins($protocol, $filesystem);
        FlysystemStreamWrapper::$filesystems[$protocol] = $filesystem;

        return $protocol;
    }

    protected function getProtocol()
    {
        return $this->protocol;
    }
}
