<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Configuration;

use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionLoaderInterface;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract;
use Heptacom\HeptaConnect\Core\Configuration\PortalNodeConfigurationInstructionProcessor;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalRegistryInterface;
use Heptacom\HeptaConnect\Core\Portal\PackageQueryMatcher;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortalExtension;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortalExtension;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ClosureInstructionToken::class)]
#[CoversClass(InstructionTokenContract::class)]
#[CoversClass(PortalNodeConfigurationInstructionProcessor::class)]
#[CoversClass(PackageQueryMatcher::class)]
#[CoversClass(PackageContract::class)]
#[CoversClass(PortalCollection::class)]
#[CoversClass(PortalExtensionCollection::class)]
#[CoversClass(PortalNodeKeyCollection::class)]
#[CoversClass(UnsupportedStorageKeyException::class)]
#[CoversClass(AbstractCollection::class)]
final class PortalNodeConfigurationInstructionProcessorTest extends TestCase
{
    public function testMatchPortalClassName(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalRegistry = $this->createMock(PortalRegistryInterface::class);
        $instructionLoader = $this->createMock(InstructionLoaderInterface::class);

        $storageKeyGenerator->method('deserialize')
            ->willReturnCallback(static function (string $s) use ($portalNodeKey) {
                if (\in_array($s, ['PortalNode:1234', 'portal-alias'], true)) {
                    return $portalNodeKey;
                }

                throw new UnsupportedStorageKeyException(PortalNodeKeyInterface::class);
            });

        $portalNodeKey->method('equals')
            ->willReturnCallback(static fn (StorageKeyInterface $s) => $s === $portalNodeKey);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();
        $portalNodeKey->method('withAlias')->willReturnSelf();
        $storageKeyGenerator->method('serialize')
            ->with($portalNodeKey)
            ->willReturn('PortalNode:1234');
        $portalRegistry->method('getPortal')
            ->with($portalNodeKey)
            ->willReturn(new FooBarPortal());
        $portalRegistry->method('getPortalExtensions')
            ->with($portalNodeKey)
            ->willReturn(new PortalExtensionCollection([new FooBarPortalExtension()]));
        $instructionLoader->method('loadInstructions')->willReturn([
            new ClosureInstructionToken(FooBarPortal::class, static fn (\Closure $l) => \array_replace($l(), ['portal-class' => true])),
            new ClosureInstructionToken(FooBarPortalExtension::class, static fn (\Closure $l) => \array_replace($l(), ['portal-ext-class' => true])),
            new ClosureInstructionToken('PortalNode:1234', static fn (\Closure $l) => \array_replace($l(), ['key' => true])),
            new ClosureInstructionToken('portal-alias', static fn (\Closure $l) => \array_replace($l(), ['portal-alias' => true])),

            new ClosureInstructionToken(PortalContract::class, static fn (\Closure $l) => \array_replace($l(), ['portal-base-class' => true])),
            new ClosureInstructionToken(PortalExtensionContract::class, static fn (\Closure $l) => \array_replace($l(), ['portal-ext-base-class' => true])),

            new ClosureInstructionToken(UninstantiablePortal::class, static fn (\Closure $l) => \array_replace($l(), ['wrong-portal-class' => true])),
            new ClosureInstructionToken(UninstantiablePortalExtension::class, static fn (\Closure $l) => \array_replace($l(), ['wrong-portal-ext-class' => true])),
            new ClosureInstructionToken(LoggerInterface::class, static fn (\Closure $l) => \array_replace($l(), ['wrong-interface' => true])),
            new ClosureInstructionToken('PortalNode:abcd', static fn (\Closure $l) => \array_replace($l(), ['wrong-key' => true])),
            new ClosureInstructionToken('foo-bar', static fn (\Closure $l) => \array_replace($l(), ['wrong-portal-alias' => true])),
        ]);

        $processor = new PortalNodeConfigurationInstructionProcessor(
            $logger,
            $portalRegistry,
            new PackageQueryMatcher($storageKeyGenerator),
            [$instructionLoader]
        );

        static::assertSame([
            'portal-class' => true,
            'portal-ext-class' => true,
            'key' => true,
            'portal-alias' => true,
            'portal-base-class' => true,
            'portal-ext-base-class' => true,
        ], $processor->read($portalNodeKey, static fn () => []));
    }
}
