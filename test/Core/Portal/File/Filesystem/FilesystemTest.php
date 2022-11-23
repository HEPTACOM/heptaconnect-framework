<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal\File\Filesystem;

use Heptacom\HeptaConnect\Core\File\Filesystem\StreamUriSchemePathConverter;
use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\Filesystem;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\File\Filesystem\StreamUriSchemePathConverter
 * @covers \Heptacom\HeptaConnect\Core\Portal\File\Filesystem\Filesystem
 * @covers \Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Exception\UnexpectedFormatOfUriException
 */
class FilesystemTest extends TestCase
{
    public function testToStoragePathWorksWithPathOnlyUri(): void
    {
        $filesystem = $this->createFilesystem();
        static::assertEquals('heptaconnect-portal-node+123://this-is-a-file.csv', $filesystem->toStoragePath('this-is-a-file.csv'));
        static::assertEquals('heptaconnect-portal-node+123://this-is-a-file.csv', $filesystem->toStoragePath('/this-is-a-file.csv'));
        static::assertEquals('heptaconnect-portal-node+123://nested/path/this-is-a-file.csv', $filesystem->toStoragePath('nested/path/this-is-a-file.csv'));
        static::assertEquals('heptaconnect-portal-node+123://nested/path/this-is-a-file.csv', $filesystem->toStoragePath('/nested/path/this-is-a-file.csv'));
        static::assertEquals('heptaconnect-portal-node+123://foobar.d/this-is-a-file.csv', $filesystem->toStoragePath('foobar.d/this-is-a-file.csv'));
        static::assertEquals('heptaconnect-portal-node+123://foobar.d/this-is-a-file.csv', $filesystem->toStoragePath('/foobar.d/this-is-a-file.csv'));
        static::assertEquals('heptaconnect-portal-node+123://', $filesystem->toStoragePath(''));
        static::assertEquals('heptaconnect-portal-node+123://', $filesystem->toStoragePath('/'));
    }

    public function testToStoragePathFailsWithSchemaInUri(): void
    {
        static::expectExceptionCode(1666942801);

        $this->createFilesystem()->toStoragePath('http://this-is-a-url.csv');
    }

    public function testToStoragePathFailsWithPortInUri(): void
    {
        static::expectExceptionCode(1666942800);

        $this->createFilesystem()->toStoragePath(':123/this-is-a-url.csv');
    }

    public function testToStoragePathFailsWithQueryInUri(): void
    {
        static::expectExceptionCode(1666942803);

        $this->createFilesystem()->toStoragePath('this-is-a-url.csv?foo=bar');
    }

    public function testToStoragePathFailsWithFragmentInUri(): void
    {
        static::expectExceptionCode(1666942804);

        $this->createFilesystem()->toStoragePath('this-is-a-url.csv#button');
    }

    public function testFromStoragePathWorksWithRightSchemeInUri(): void
    {
        $filesystem = $this->createFilesystem();
        static::assertSame('this-is-a-url.csv', $filesystem->fromStoragePath('heptaconnect-portal-node+123://this-is-a-url.csv'));
        static::assertSame('dir.d/tested/this-is-a-url.csv', $filesystem->fromStoragePath('heptaconnect-portal-node+123://dir.d/tested/this-is-a-url.csv'));
    }

    public function testFromStoragePathFailsWithWrongSchemeInUri(): void
    {
        static::expectExceptionCode(1666942811);

        $this->createFilesystem()->fromStoragePath('http:this-is-a-url.csv');
    }

    public function testFromStoragePathFailsWithNonUri(): void
    {
        static::expectExceptionCode(1666942811);

        $this->createFilesystem()->fromStoragePath('hello world');
    }

    public function testFromStoragePathFailsWithPortInUri(): void
    {
        static::expectExceptionCode(1666942812);

        $this->createFilesystem()->fromStoragePath('heptaconnect-portal-node+123://this-is-a-url.csv:123');
    }

    public function testFromStoragePathFailsWithQueryInUri(): void
    {
        static::expectExceptionCode(1666942813);

        $this->createFilesystem()->fromStoragePath('heptaconnect-portal-node+123://this-is-a-url.csv?foo=bar');
    }

    public function testFromStoragePathFailsWithFragmentInUri(): void
    {
        static::expectExceptionCode(1666942814);

        $this->createFilesystem()->fromStoragePath('heptaconnect-portal-node+123://this-is-a-url.csv#form');
    }

    private function createFilesystem(): Filesystem
    {
        return new Filesystem(new StreamUriSchemePathConverter(Psr17FactoryDiscovery::findUriFactory(), 'heptaconnect-portal-node+123'));
    }
}
