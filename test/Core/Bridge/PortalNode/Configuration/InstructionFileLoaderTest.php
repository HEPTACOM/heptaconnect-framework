<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Bridge\PortalNode\Configuration;

use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionFileLoader;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionTokenCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
#[CoversClass(ClosureInstructionToken::class)]
#[CoversClass(InstructionTokenCollection::class)]
#[CoversClass(InstructionTokenContract::class)]
#[CoversClass(Config::class)]
#[CoversClass(InstructionFileLoader::class)]
final class InstructionFileLoaderTest extends TestCase
{
    public function testFailOnNonExistingFile(): void
    {
        try {
            (new InstructionFileLoader(__DIR__ . '/does-not-exists.php'))->loadInstructions();
        } catch (\Throwable $throwable) {
            static::assertSame(1645611612, $throwable->getCode());
            static::assertStringContainsString('configuration file /', $throwable->getMessage());

            static::assertSame(1717858650, $throwable->getPrevious()?->getCode());
            static::assertStringContainsString('File /', $throwable->getPrevious()?->getMessage());
            static::assertStringContainsString('/does-not-exists.php does not exist', $throwable->getPrevious()?->getMessage());
        }
    }

    public function testExistingFile(): void
    {
        static::assertSame(1, (new InstructionFileLoader(__DIR__ . '/../../../Fixture/_files/portal-node-configuration.php'))->loadInstructions()->count());
    }
}
