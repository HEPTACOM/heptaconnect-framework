<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal\File\Filesystem;

use Heptacom\HeptaConnect\Core\Bridge\File\PortalNodeFilesystemStreamWrapperFactoryInterface;
use Heptacom\HeptaConnect\Core\File\Filesystem\StreamUriSchemePathConverter;
use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\FilesystemFactory;
use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\PathToUriConvertingStreamWrapper;
use Heptacom\HeptaConnect\Core\Storage\Filesystem\PrefixFilesystem;
use Heptacom\HeptaConnect\Core\Test\Fixture\FlysystemStreamWrapper;
use Heptacom\HeptaConnect\Core\Test\Fixture\TwistorFlysystemStreamWrapper;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Http\Discovery\Psr17FactoryDiscovery;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as FlysystemFilesystem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\File\Filesystem\StreamUriSchemePathConverter
 * @covers \Heptacom\HeptaConnect\Core\File\Filesystem\StreamWrapperRegistry
 * @covers \Heptacom\HeptaConnect\Core\Portal\File\Filesystem\Filesystem
 * @covers \Heptacom\HeptaConnect\Core\Portal\File\Filesystem\FilesystemFactory
 * @covers \Heptacom\HeptaConnect\Core\Portal\File\Filesystem\PathToUriConvertingStreamWrapper
 * @covers \Heptacom\HeptaConnect\Core\Portal\File\Filesystem\UriToPathConvertingStreamWrapper
 * @covers \Heptacom\HeptaConnect\Core\Storage\Filesystem\AbstractFilesystem
 * @covers \Heptacom\HeptaConnect\Core\Storage\Filesystem\PrefixAdapter
 * @covers \Heptacom\HeptaConnect\Core\Storage\Filesystem\PrefixFilesystem
 */
final class FilesystemFactoryTest extends TestCase
{
    public function testFileAccessLifecycle(): void
    {
        $storageKeyGenerator = new class() extends StorageKeyGeneratorContract {
            public function serialize(StorageKeyInterface $key): string
            {
                return 'PortalNode:' . \json_decode(\json_encode($key), true);
            }

            public function generateKeys(string $keyClassName, int $count): iterable
            {
                return [];
            }
        };

        $fixtureFolder = __DIR__ . '/../../../Fixture/_files/portal_filesystem';

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('jsonSerialize')->willReturn('a212c53a38e6498486c5c4faadf1f97f');
        $portalNodeKey->method('withoutAlias')->willReturnSelf();

        $uriFactory = Psr17FactoryDiscovery::findUriFactory();

        $flysystem = new FlysystemFilesystem(new Local($fixtureFolder));
        $flysystem = new PrefixFilesystem($flysystem, 'prefix');
        $flyProtocol = TwistorFlysystemStreamWrapper::registerFilesystem($flysystem);
        $fixtureFolder .= '/prefix';

        $flysystemStreamWrapper = new TwistorFlysystemStreamWrapper();
        $flysystemStreamWrapper->setFilesystem($flysystem);
        $flysystemStreamWrapper->setProtocol($flyProtocol);

        $streamWrapper = new FlysystemStreamWrapper();
        $streamWrapper->setDecorated($flysystemStreamWrapper);

        $flysystemProtocol = new PathToUriConvertingStreamWrapper();
        $flysystemProtocol->setConverter(new StreamUriSchemePathConverter($uriFactory, $flyProtocol));
        $flysystemProtocol->setDecorated($streamWrapper);

        $streamWrapperFactory = $this->createMock(PortalNodeFilesystemStreamWrapperFactoryInterface::class);
        $streamWrapperFactory->method('create')->willReturn($flysystemProtocol);

        $filesystemFactory = new FilesystemFactory($streamWrapperFactory, $uriFactory, $storageKeyGenerator);
        $filesystem = $filesystemFactory->create($portalNodeKey);

        $filePath = $filesystem->toStoragePath('foobar.txt');
        $directoryPath = $filesystem->toStoragePath('directory.d');
        $firstFilePath = $filesystem->toStoragePath('directory.d/index.html');
        $subfilePath = $filesystem->toStoragePath('directory.d/foobaz.txt');

        // clean up
        if (\is_file($subfilePath)) {
            \unlink($subfilePath);
        }

        if (\is_file($firstFilePath)) {
            \unlink($firstFilePath);
        }

        if (\is_file($filePath)) {
            \unlink($filePath);
        }

        if (\is_dir($directoryPath)) {
            \rmdir($directoryPath);
        }

        \touch($filePath);
        \mkdir($directoryPath);
        \touch($firstFilePath);
        \rename($firstFilePath, $subfilePath);

        static::assertFileExists($filePath);
        static::assertFileExists($fixtureFolder . '/foobar.txt');
        static::assertFileExists($fixtureFolder . '/directory.d/foobaz.txt');

        $dir_iterator = new \RecursiveDirectoryIterator($fixtureFolder);
        $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
        $files = \array_values(\iterable_to_array(\iterable_map(
            $iterator,
            static fn (\SplFileInfo $file): string => $file->getFilename()
        )));

        \sort($files);
        static::assertSame([
            '.',
            '.',
            '..',
            '..',
            'directory.d',
            'foobar.txt',
            'foobaz.txt',
        ], $files);

        $dir_iterator = new \RecursiveDirectoryIterator($filesystem->toStoragePath('.'));
        $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
        $files = \array_values(\iterable_to_array(\iterable_map(
            $iterator,
            static fn (\SplFileInfo $file): string => $filesystem->fromStoragePath($file->getFilename())
        )));

        \sort($files);
        static::assertSame([
            'directory.d',
            'foobar.txt',
            // TODO find out why it is not recursive
            // 'foobaz.txt',
        ], $files);

        \file_put_contents($filePath, 'foobar');
        static::assertStringEqualsFile($filePath, 'foobar');

        $subfileStream = \fopen($subfilePath, 'w+b');
        \fwrite($subfileStream, \str_repeat('a', 1024));
        \fflush($subfileStream);

        static::assertSame(1024, \ftell($subfileStream));
        \rewind($subfileStream);
        static::assertSame(0, \ftell($subfileStream));
        \ftruncate($subfileStream, 512);
        \fseek($subfileStream, 0, \SEEK_END);
        static::assertSame(512, \ftell($subfileStream));

        \flock($subfileStream, \LOCK_EX);

        \fclose($subfileStream);

        \unlink($subfilePath);
        \unlink($filePath);
        \rmdir($directoryPath);
        static::assertFileDoesNotExist($filePath);
        static::assertFileDoesNotExist($fixtureFolder . '/foobar.txt');
    }
}
