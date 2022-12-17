<?php

declare(strict_types=1);

namespace HeptacomFixture\TestSuitePortal\TestPortal\Test\TestSuite;

use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\Contract\FilesystemFactoryInterface;
use Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Contract\FilesystemInterface;
use Heptacom\HeptaConnect\TestSuite\Portal\AbstractTestCase;

/**
 * @covers \Heptacom\HeptaConnect\TestSuite\Portal\AbstractTestCase
 */
final class StorageAccessorTest extends AbstractTestCase
{
    public function testNoDataInFilesystem(): void
    {
        $container = $this->getContainer();
        /** @var FilesystemInterface $filesystem */
        $filesystem = $container->get(FilesystemInterface::class);
        $notExists = $filesystem->toStoragePath('file-that-does-not-exist.txt');

        static::assertFileDoesNotExist($notExists);

        \touch($notExists);

        static::assertFileExists($notExists);
    }

    public function testHasDataInFilesystem(): void
    {
        $container = $this->getContainer([
            FilesystemFactoryInterface::class => $this->createFilesystemFactoryForDirectory(__DIR__ . '/../filesystems/storage-accessor/has-data-in-filesystem')
        ]);

        /** @var FilesystemInterface $filesystem */
        $filesystem = $container->get(FilesystemInterface::class);
        $exists = $filesystem->toStoragePath('flag.txt');

        static::assertFileExists($exists);
        static::assertSame('FooBar' ,\trim(\file_get_contents($exists)));
    }
}
