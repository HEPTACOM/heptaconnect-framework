<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Exception\UnexpectedPortalNodeException;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\PortalNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exception\UnexpectedPortalNodeException
 */
class UnexpectedPortalNodeExceptionTest extends TestCase
{
    public function testExceptionDetectsTypeCorrectly(): void
    {
        $e = new UnexpectedPortalNodeException(null);
        static::assertEquals('null', $e->getType());
        static::assertStringContainsString($e->getType(), $e->getMessage());
        static::assertEquals(0, $e->getCode());

        $e = new UnexpectedPortalNodeException(new PortalNode());
        static::assertEquals(PortalNode::class, $e->getType());
        static::assertStringContainsString($e->getType(), $e->getMessage());
        static::assertEquals(0, $e->getCode());
    }
}
