<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Bridge\PortalNode\Configuration;

use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken;
use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken
 * @covers \Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\PortalNodeConfigurationHelper
 */
final class ConfigTest extends TestCase
{
    public function testMergeChain(): void
    {
        $config = new Config();
        Config::merge('portal-a', [
            'key' => 'value',
            'list' => [
                1,
                2,
                3,
                4,
                5,
            ],
        ], false);
        $instructions = $config->buildInstructions();
        static::assertCount(1, $instructions);
        $instruction = $instructions[0];
        static::assertInstanceOf(ClosureInstructionToken::class, $instruction);

        $result = $instruction->getClosure()(static fn () => [
            'list' => [
                1,
                2,
                3,
            ],
        ]);

        static::assertSame([
            'list' => [
                1,
                2,
                3,
                4,
                5,
            ],
            'key' => 'value',
        ], $result);
    }

    public function testMergeRecursiveChain(): void
    {
        $config = new Config();
        Config::merge('portal-a', [
            'key' => 'value',
            'list' => [
                1,
                2,
                3,
                4,
                5,
            ],
        ]);
        $instructions = $config->buildInstructions();
        static::assertCount(1, $instructions);
        $instruction = $instructions[0];
        static::assertInstanceOf(ClosureInstructionToken::class, $instruction);

        $result = $instruction->getClosure()(static fn () => [
            'list' => [
                1,
                2,
                3,
            ],
        ]);

        static::assertSame([
            'list' => [
                1,
                2,
                3,
                1,
                2,
                3,
                4,
                5,
            ],
            'key' => 'value',
        ], $result);
    }

    public function testReplaceChain(): void
    {
        $config = new Config();
        Config::replace('portal-a', [
            'key' => 'value',
            'list' => [
                7,
            ],
        ], false);
        $instructions = $config->buildInstructions();
        static::assertCount(1, $instructions);
        $instruction = $instructions[0];
        static::assertInstanceOf(ClosureInstructionToken::class, $instruction);

        $result = $instruction->getClosure()(static fn () => [
            'list' => [
                1,
                2,
                3,
            ],
        ]);

        static::assertSame([
            'list' => [
                7,
            ],
            'key' => 'value',
        ], $result);
    }

    public function testReplaceRecursiveChain(): void
    {
        $config = new Config();
        Config::replace('portal-a', [
            'key' => 'value',
            'list' => [
                7,
            ],
        ]);
        $instructions = $config->buildInstructions();
        static::assertCount(1, $instructions);
        $instruction = $instructions[0];
        static::assertInstanceOf(ClosureInstructionToken::class, $instruction);

        $result = $instruction->getClosure()(static fn () => [
            'list' => [
                1,
                2,
                3,
            ],
        ]);

        static::assertSame([
            'list' => [
                7,
                2,
                3,
            ],
            'key' => 'value',
        ], $result);
    }

    public function testSetChain(): void
    {
        $config = new Config();
        Config::set('portal-a', [
            'list' => [
                7,
            ],
        ]);
        $instructions = $config->buildInstructions();
        static::assertCount(1, $instructions);
        $instruction = $instructions[0];
        static::assertInstanceOf(ClosureInstructionToken::class, $instruction);

        $result = $instruction->getClosure()(static fn () => [
            'key' => 'value',
            'list' => [
                1,
                2,
                3,
            ],
        ]);

        static::assertSame([
            'list' => [
                7,
            ],
        ], $result);
    }

    public function testResetChain(): void
    {
        $config = new Config();
        Config::reset('portal-a', [
            'key',
            'nested' => [
                'list',
            ],
        ]);
        $instructions = $config->buildInstructions();
        static::assertCount(1, $instructions);
        $instruction = $instructions[0];
        static::assertInstanceOf(ClosureInstructionToken::class, $instruction);

        $result = $instruction->getClosure()(static fn () => [
            'key' => 'value',
            'list' => [
                1,
                2,
                3,
            ],
            'nested' => [
                'list' => [
                    1,
                ],
                'foo' => 'bar',
            ],
        ]);

        static::assertSame([
            'list' => [
                1,
                2,
                3,
            ],
            'nested' => [
                'foo' => 'bar',
            ],
        ], $result);
    }

    public function testGetHelper(): void
    {
        static::assertSame([], Config::helper()->array([], [])());
    }
}
