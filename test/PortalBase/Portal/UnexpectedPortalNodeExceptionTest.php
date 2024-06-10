<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnexpectedPortalNodeException;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\Portal;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PackageContract::class)]
#[CoversClass(UnexpectedPortalNodeException::class)]
final class UnexpectedPortalNodeExceptionTest extends TestCase
{
    public function testExceptionDetectsTypeCorrectly(): void
    {
        $e = new UnexpectedPortalNodeException(null);
        static::assertEquals('null', $e->getType());
        static::assertStringContainsString($e->getType(), $e->getMessage());
        static::assertEquals(0, $e->getCode());

        $e = new UnexpectedPortalNodeException(new Portal());
        static::assertEquals(Portal::class, $e->getType());
        static::assertStringContainsString($e->getType(), $e->getMessage());
        static::assertEquals(0, $e->getCode());
    }
}
