<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Bridge\PortalNode\Configuration;

use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionFileLoader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionFileLoader
 */
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
