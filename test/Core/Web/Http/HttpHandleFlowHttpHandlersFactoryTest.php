<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleFlowHttpHandlersFactory;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandleFlowHttpHandlersFactory
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 */
final class HttpHandleFlowHttpHandlersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $factory = new HttpHandleFlowHttpHandlersFactory();
        $result = $factory->createHttpHandlers($this->createMock(PortalNodeKeyInterface::class), '');

        static::assertCount(0, $result);
    }
}
