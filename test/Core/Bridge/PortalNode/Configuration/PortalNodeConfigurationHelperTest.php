<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Bridge\PortalNode\Configuration;

use Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\PortalNodeConfigurationHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PortalNodeConfigurationHelper::class)]
final class PortalNodeConfigurationHelperTest extends TestCase
{
    public function testParseAndMapArray(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->array([
            'list' => [
                1,
                2,
                3,
            ],
            'foo' => 'bar',
            'nested' => [
                'foo' => 'bar',
            ],
        ], [
            'result' => [
                'list',
                'nested' => [
                    'foo',
                    'nested.foo',
                ],
                'not' => 'found',
            ],
        ]);
        static::assertSame([
            'result' => [
                [
                    1,
                    2,
                    3,
                ],
                'nested' => [
                    'bar',
                    'bar',
                ],
                'not' => null,
            ],
        ], $resolver());
    }

    public function testParseAndKeepJson(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->json(__DIR__ . '/../../../Fixture/_files/portal-node-configuration.json');
        static::assertSame([
            'portal-a' => [
                'foo' => 'bar',
            ],
            'password' => 'secret',
        ], $resolver());
    }

    public function testParseAndFailOnInvalidJson(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->json(__DIR__ . '/../../../Fixture/_files/portal-node-configuration-invalid.json');

        try {
            $resolver();
            static::fail();
        } catch (\Throwable $throwable) {
            static::assertNotSame(0, $throwable->getCode());
        }
    }

    public function testParseAndMapJson(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->json(__DIR__ . '/../../../Fixture/_files/portal-node-configuration.json', [
            'result' => [
                'list',
                'nested' => [
                    'password',
                    'portal-a.foo',
                ],
                'not' => 'found',
            ],
        ]);
        static::assertSame([
            'result' => [
                null,
                'nested' => [
                    'secret',
                    'bar',
                ],
                'not' => null,
            ],
        ], $resolver());
    }

    public function testParseAndKeepIni(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->ini(__DIR__ . '/../../../Fixture/_files/portal-node-configuration.ini');
        static::assertSame([
            'portal-a' => [
                'key' => 'value-a',
                'password' => 'secret-a',
            ],
            'portal-b' => [
                'key' => 'value-b',
                'password' => 'secret-b',
            ],
        ], $resolver());
    }

    public function testParseAndFailOnInvalidIni(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->ini(__DIR__ . '/../../../Fixture/_files/portal-node-configuration-invalid.ini');

        try {
            $resolver();
            static::fail();
        } catch (\Throwable $throwable) {
            static::assertNotSame(0, $throwable->getCode());
        }
    }

    public function testParseAndMapIni(): void
    {
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->ini(__DIR__ . '/../../../Fixture/_files/portal-node-configuration.ini', [
            'result' => [
                'portal-a',
                'nested' => [
                    'portal-b.key',
                    'portal-a.secret',
                ],
                'not' => 'found',
            ],
        ]);
        static::assertSame([
            'result' => [
                [
                    'key' => 'value-a',
                    'password' => 'secret-a',
                ],
                'nested' => [
                    'value-b',
                    null,
                ],
                'not' => null,
            ],
        ], $resolver());
    }

    public function testMapEnv(): void
    {
        \putenv('__UNIT_TEST_FOO=BAR');
        \putenv('__UNIT_TEST_PORTAL_A=SECRET');
        $helper = new PortalNodeConfigurationHelper();
        $resolver = $helper->env([
            'result' => [
                '__UNIT_TEST_FOO',
                'nested' => [
                    '__UNIT_TEST_PORTAL_A',
                    '__UNIT_TEST_PORTAL_B',
                ],
                'not' => 'found',
            ],
        ]);
        static::assertSame([
            'result' => [
                'BAR',
                'nested' => [
                    'SECRET',
                    false,
                ],
                'not' => false,
            ],
        ], $resolver());
        \putenv('__UNIT_TEST_FOO=');
        \putenv('__UNIT_TEST_PORTAL_A=');
    }
}
