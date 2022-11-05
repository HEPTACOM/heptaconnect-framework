<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use League\Flysystem\FilesystemInterface;
use Twistor\Flysystem\Plugin\ForcedRename;
use Twistor\Flysystem\Plugin\Mkdir;
use Twistor\Flysystem\Plugin\Rmdir;
use Twistor\Flysystem\Plugin\Touch;
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

        // begin copy FlysystemStreamWrapper::registerPlugins($protocol, $filesystem);
        $filesystem->addPlugin(new ForcedRename());
        $filesystem->addPlugin(new Mkdir());
        $filesystem->addPlugin(new Rmdir());

        // was Twistor\Flysystem\Plugin\Stat before but this is broken
        $stat = new TwistorFlysystemPluginStat(
            FlysystemStreamWrapper::$config[$protocol]['permissions'],
            FlysystemStreamWrapper::$config[$protocol]['metadata']
        );

        $filesystem->addPlugin($stat);
        $filesystem->addPlugin(new Touch());
        // end copy FlysystemStreamWrapper::registerPlugins($protocol, $filesystem);

        FlysystemStreamWrapper::$filesystems[$protocol] = $filesystem;

        return $protocol;
    }

    protected function getProtocol()
    {
        return $this->protocol;
    }
}
