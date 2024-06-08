<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract;
use Heptacom\HeptaConnect\Core\Reception\ReceiveContext;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\EntityStatusContract;
use PHPUnit\Framework\TestCase;

/**
 * @covers  \Heptacom\HeptaConnect\Core\Reception\ReceiveContext
 */
final class ReceiveContextTest extends TestCase
{
    public function testAddEventListener(): void
    {
        $context = new ReceiveContext(
            $this->createMock(PortalNodeContainerFacadeContract::class),
            null,
            $this->createMock(EntityStatusContract::class),
            []
        );

        $value = false;

        $context->getEventDispatcher()->addListener('test-event', function () use (&$value) {
            $value = true;
        });

        $context->getEventDispatcher()->dispatch(new \stdClass(), 'test-event');

        static::assertTrue($value, 'Event listener was not called');
    }
}
