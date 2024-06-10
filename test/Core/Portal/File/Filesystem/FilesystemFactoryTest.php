<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal\File\Filesystem;

use Heptacom\HeptaConnect\Core\Bridge\File\PortalNodeFilesystemStreamProtocolProviderInterface;
use Heptacom\HeptaConnect\Core\File\Filesystem\RewritePathStreamWrapper;
use Heptacom\HeptaConnect\Core\File\Filesystem\StreamUriSchemePathConverter;
use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\Filesystem;
use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\FilesystemFactory;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Http\Discovery\Psr17FactoryDiscovery;
use League\Flysystem\Filesystem as FlysystemFilesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use M2MTech\FlysystemStreamWrapper\FlysystemStreamWrapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RewritePathStreamWrapper::class)]
#[CoversClass(StreamUriSchemePathConverter::class)]
#[CoversClass(Filesystem::class)]
#[CoversClass(FilesystemFactory::class)]
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

        // TODO Fix Flysystem compatibility
        $flysystem = new FlysystemFilesystem(new LocalFilesystemAdapter($fixtureFolder . '/prefix'));
        $fixtureFolder .= '/prefix';

        FlysystemStreamWrapper::register('test-stream', $flysystem);

        $streamProtocolProvider = $this->createMock(PortalNodeFilesystemStreamProtocolProviderInterface::class);
        $streamProtocolProvider->method('provide')->willReturn('test-stream');

        $filesystemFactory = new FilesystemFactory($streamProtocolProvider, $uriFactory, $storageKeyGenerator);
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
            static fn (\SplFileInfo $file): string => $file->getFilename()
        )));

        // TODO fix recursive listing
        if (\PHP_VERSION_ID < 80100) {
            \sort($files);
            static::assertSame([
                'directory.d',
                'foobar.txt',
                'foobaz.txt',
            ], $files);
        }

        static::assertSame(6, \file_put_contents($filePath, 'foobar'));
        static::assertStringEqualsFile($filePath, 'foobar');

        $subfileStream = \fopen($subfilePath, 'w+b');
        static::assertSame(1024, \fwrite($subfileStream, \str_repeat('a', 1024)));
        static::assertTrue(\fflush($subfileStream));

        static::assertSame(1024, \ftell($subfileStream));
        static::assertTrue(\rewind($subfileStream));
        static::assertSame(0, \ftell($subfileStream));
        static::assertTrue(\ftruncate($subfileStream, 512));
        static::assertSame(0, \fseek($subfileStream, 0, \SEEK_END));
        static::assertSame(512, \ftell($subfileStream));

        static::assertTrue(\flock($subfileStream, \LOCK_EX));

        static::assertTrue(\fclose($subfileStream));

        static::assertTrue(\unlink($subfilePath));
        static::assertTrue(\unlink($filePath));
        static::assertTrue(\rmdir($directoryPath));
        static::assertFileDoesNotExist($filePath);
        static::assertFileDoesNotExist($fixtureFolder . '/foobar.txt');
    }
}
