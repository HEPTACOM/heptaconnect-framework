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
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\UnsupportedSortingException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteBrowseUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
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
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\UnsupportedSortingException
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
        static::assertSame([
            RouteOverviewCriteria::FIELD_CREATED => RouteOverviewCriteria::SORT_DESC,
        ], $storageCriteria->getSort());

        $storageCriteria = null;

        $criteria = new RouteBrowseCriteria();
        $criteria->setPage(1);
        $criteria->setPageSize(52);
        $criteria->setCapabilityFilter(new StringCollection([RouteCapability::RECEPTION]));
        $criteria->setTargetPortalNodeKeyFilter(new PortalNodeKeyCollection([$portalNodeKey]));
        $criteria->setSourcePortalNodeKeyFilter(new PortalNodeKeyCollection([$portalNodeKey]));
        $criteria->setEntityTypeFilter(new ClassStringReferenceCollection([FooBarEntity::class()]));
        $criteria->setSort([
            RouteBrowseCriteria::FIELD_ENTITY_TYPE => RouteBrowseCriteria::SORT_ASC,
            RouteBrowseCriteria::FIELD_SOURCE => RouteBrowseCriteria::SORT_DESC,
        ]);

        \iterable_to_array($action->browse($criteria, $this->createUiActionContext()));

        static::assertNotNull($storageCriteria);
        static::assertCount(1, $storageCriteria->getTargetPortalNodeKeyFilter());
        static::assertCount(1, $storageCriteria->getSourcePortalNodeKeyFilter());
        static::assertCount(1, $storageCriteria->getEntityTypeFilter());
        static::assertSame([RouteCapability::RECEPTION], $storageCriteria->getCapabilityFilter()->asArray());
        static::assertSame(1, $storageCriteria->getPage());
        static::assertSame(52, $storageCriteria->getPageSize());
        static::assertSame([
            RouteOverviewCriteria::FIELD_ENTITY_TYPE => RouteOverviewCriteria::SORT_ASC,
            RouteOverviewCriteria::FIELD_SOURCE => RouteOverviewCriteria::SORT_DESC,
        ], $storageCriteria->getSort());

        $storageCriteria = null;

        $criteria = new RouteBrowseCriteria();
        $criteria->setSort([
            RouteBrowseCriteria::FIELD_ENTITY_TYPE => 'random',
        ]);

        try {
            \iterable_to_array($action->browse($criteria, $this->createUiActionContext()));

            throw new \Exception('No exception thrown');
        } catch (UnsupportedSortingException $throwable) {
            static::assertSame(1670625000, $throwable->getCode());
            static::assertSame($throwable->getValue(), 'random');
            static::assertTrue($throwable->getAvailableValues()->contains(BrowseCriteriaContract::SORT_DESC));
            static::assertTrue($throwable->getAvailableValues()->contains(BrowseCriteriaContract::SORT_ASC));
            static::assertCount(2, $throwable->getAvailableValues());
        }

        $storageCriteria = null;

        $criteria = new RouteBrowseCriteria();
        $criteria->setSort([
            'unknown column' => RouteOverviewCriteria::SORT_ASC,
        ]);

        try {
            \iterable_to_array($action->browse($criteria, $this->createUiActionContext()));

            throw new \Exception('No exception thrown');
        } catch (UnsupportedSortingException $throwable) {
            static::assertSame(1670625001, $throwable->getCode());
            static::assertSame($throwable->getValue(), 'unknown column');
            static::assertTrue($throwable->getAvailableValues()->contains(RouteBrowseCriteria::FIELD_CREATED));
            static::assertTrue($throwable->getAvailableValues()->contains(RouteBrowseCriteria::FIELD_ENTITY_TYPE));
            static::assertTrue($throwable->getAvailableValues()->contains(RouteBrowseCriteria::FIELD_SOURCE));
            static::assertTrue($throwable->getAvailableValues()->contains(RouteBrowseCriteria::FIELD_TARGET));
            static::assertCount(4, $throwable->getAvailableValues());
        }

        static::assertNull($storageCriteria);
    }
}
