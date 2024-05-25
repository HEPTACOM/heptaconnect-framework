<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Bridge\PortalNode\Configuration;

use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionFileLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClosureInstructionToken::class)]
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
        }
    }

    public function testExistingFile(): void
    {
        static::assertCount(1, (new InstructionFileLoader(__DIR__ . '/../../../Fixture/_files/portal-node-configuration.php'))->loadInstructions());
    }
}
