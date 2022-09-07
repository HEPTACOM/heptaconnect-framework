<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault
 * @covers \Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault
 */
final class RouteAddUiDefaultTest extends TestCase
{
    public function testResultIsNotEmpty(): void
    {
        $defaultProvider = new RouteAddUiDefault();
        $routeAddDefault = $defaultProvider->getDefault();

        static::assertNotEmpty($routeAddDefault->getCapabilities());
        static::assertContains(RouteCapability::RECEPTION, $routeAddDefault->getCapabilities());

        foreach ($routeAddDefault->getCapabilities() as $capability) {
            static::assertTrue(RouteCapability::isKnown($capability));
        }
    }
}
