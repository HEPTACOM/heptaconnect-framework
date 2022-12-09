<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteBrowseUi;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteBrowseUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract
 */
final class RouteBrowseUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testCriteriaFilters(): void
    {
        /** @var RouteOverviewCriteria|null $storageCriteria */
        $storageCriteria = null;

        $routeOverviewAction = $this->createMock(RouteOverviewActionInterface::class);
        $routeOverviewAction->method('overview')
            ->willReturnCallback(static function (RouteOverviewCriteria $crit) use (&$storageCriteria): iterable {
                $storageCriteria = $crit;

                return [];
            });

        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $action = new RouteBrowseUi($this->createAuditTrailFactory(), $routeOverviewAction);
        $criteria = new RouteBrowseCriteria();

        \iterable_to_array($action->browse($criteria, $this->createUiActionContext()));

        static::assertNotNull($storageCriteria);
        static::assertNull($storageCriteria->getTargetPortalNodeKeyFilter());
        static::assertNull($storageCriteria->getSourcePortalNodeKeyFilter());
        static::assertNull($storageCriteria->getEntityTypeFilter());
        static::assertNull($storageCriteria->getCapabilityFilter());
        static::assertSame(0, $storageCriteria->getPage());
        static::assertSame(10, $storageCriteria->getPageSize());

        $storageCriteria = null;

        $criteria = new RouteBrowseCriteria();
        $criteria->setPage(1);
        $criteria->setPageSize(52);
        $criteria->setCapabilityFilter(new StringCollection([RouteCapability::RECEPTION]));
        $criteria->setTargetPortalNodeKeyFilter(new PortalNodeKeyCollection([$portalNodeKey]));
        $criteria->setSourcePortalNodeKeyFilter(new PortalNodeKeyCollection([$portalNodeKey]));
        $criteria->setEntityTypeFilter(new ClassStringReferenceCollection([FooBarEntity::class()]));

        \iterable_to_array($action->browse($criteria, $this->createUiActionContext()));

        static::assertNotNull($storageCriteria);
        static::assertCount(1, $storageCriteria->getTargetPortalNodeKeyFilter());
        static::assertCount(1, $storageCriteria->getSourcePortalNodeKeyFilter());
        static::assertCount(1, $storageCriteria->getEntityTypeFilter());
        static::assertSame([RouteCapability::RECEPTION], $storageCriteria->getCapabilityFilter());
        static::assertSame(1, $storageCriteria->getPage());
        static::assertSame(52, $storageCriteria->getPageSize());
    }
}
