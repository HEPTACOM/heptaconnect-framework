<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 */
final class RouteAddUiDefaultTest extends TestCase
{
    use UiActionTestTrait;

    public function testResultIsNotEmpty(): void
    {
        $defaultProvider = new RouteAddUiDefault($this->createAuditTrailFactory());
        $routeAddDefault = $defaultProvider->getDefault($this->createUiActionContext());

        static::assertNotEmpty($routeAddDefault->getCapabilities());
        static::assertContains(RouteCapability::RECEPTION, $routeAddDefault->getCapabilities());

        foreach ($routeAddDefault->getCapabilities() as $capability) {
            static::assertTrue(RouteCapability::isKnown($capability));
        }
    }
}
