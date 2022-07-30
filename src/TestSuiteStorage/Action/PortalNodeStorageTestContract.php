<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Clear\PortalNodeStorageClearCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Delete\PortalNodeStorageDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetItem;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetItems;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test portal node storage related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class PortalNodeStorageTestContract extends TestCase
{
    /**
     * Validates implementation to check keys case-sensitive and space-sensitive.
     */
    public function testCaseSensitiveAndSpaceSensitiveNaming(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeCreateAction = $facade->getPortalNodeCreateAction();
        $portalNodeDeleteAction = $facade->getPortalNodeDeleteAction();
        $portalNodeCreateResult = $portalNodeCreateAction->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
        ]));
        $portalNodeKey = $portalNodeCreateResult[0]->getPortalNodeKey();
        $set = $facade->getPortalNodeStorageSetAction();

        $set->set(new PortalNodeStorageSetPayload($portalNodeKey, new PortalNodeStorageSetItems([
            new PortalNodeStorageSetItem('foobar', 'foobar', 'string', null),
            new PortalNodeStorageSetItem('FooBar', 'FooBar', 'string', null),
            new PortalNodeStorageSetItem('foobar ', 'foobar ', 'string', null),
            new PortalNodeStorageSetItem('FooBar ', 'FooBar ', 'string', null),
            new PortalNodeStorageSetItem('different', 'value', 'string', null),
        ])));

        $get = $facade->getPortalNodeStorageGetAction();
        /** @var PortalNodeStorageGetResult[] $getResults */
        $getResults = \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'foobar',
            'FooBar',
            'foobar ',
            'FooBar ',
        ]))));

        $getValues = \array_combine(
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getStorageKey(), $getResults),
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getValue(), $getResults)
        );
        \ksort($getValues);

        static::assertSame([
            'FooBar' => 'FooBar',
            'FooBar ' => 'FooBar ',
            'foobar' => 'foobar',
            'foobar ' => 'foobar ',
        ], $getValues);

        $portalNodeDeleteAction->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ])));
    }

    /**
     * Validates implementation to check keys allow HTML like storage keys.
     */
    public function testHtmlLikeNaming(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeCreateAction = $facade->getPortalNodeCreateAction();
        $portalNodeDeleteAction = $facade->getPortalNodeDeleteAction();
        $portalNodeCreateResult = $portalNodeCreateAction->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
        ]));
        $portalNodeKey = $portalNodeCreateResult[0]->getPortalNodeKey();
        $set = $facade->getPortalNodeStorageSetAction();

        $set->set(new PortalNodeStorageSetPayload($portalNodeKey, new PortalNodeStorageSetItems([
            new PortalNodeStorageSetItem('<foobar>', '<foobar>', 'string', null),
            new PortalNodeStorageSetItem('Foo<Bar', 'Foo<Bar', 'string', null),
            new PortalNodeStorageSetItem('different', 'value', 'string', null),
        ])));

        $get = $facade->getPortalNodeStorageGetAction();
        /** @var PortalNodeStorageGetResult[] $getResults */
        $getResults = \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            '<foobar>',
            'Foo<Bar',
        ]))));

        $getValues = \array_combine(
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getStorageKey(), $getResults),
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getValue(), $getResults)
        );
        \ksort($getValues);

        static::assertSame([
            '<foobar>' => '<foobar>',
            'Foo<Bar' => 'Foo<Bar',
        ], $getValues);

        $portalNodeDeleteAction->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ])));
    }

    /**
     * Validates a complete portal node storage entry "lifecycle" can be managed with the storage. It covers setting, deleting, running out of time.
     */
    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeCreate = $facade->getPortalNodeCreateAction();
        $portalNodeDelete = $facade->getPortalNodeDeleteAction();
        $clear = $facade->getPortalNodeStorageClearAction();
        $delete = $facade->getPortalNodeStorageDeleteAction();
        $get = $facade->getPortalNodeStorageGetAction();
        $set = $facade->getPortalNodeStorageSetAction();

        $firstPortalNode = $portalNodeCreate->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
        ]))->first();

        static::assertInstanceOf(PortalNodeCreateResult::class, $firstPortalNode);

        $portalNodeKey = $firstPortalNode->getPortalNodeKey();

        $set->set(new PortalNodeStorageSetPayload($portalNodeKey, new PortalNodeStorageSetItems([
            new PortalNodeStorageSetItem('foo', 'bar', 'string', null),
            new PortalNodeStorageSetItem('foo-timed', 'bar', 'string', new \DateInterval('PT5S')),
            new PortalNodeStorageSetItem('different', 'value', 'string', null),
        ])));

        /** @var PortalNodeStorageGetResult[] $getResults */
        $getResults = \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'foo',
            'foo-timed',
        ]))));

        static::assertCount(2, $getResults);

        $getValues = \array_combine(
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getStorageKey(), $getResults),
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getValue(), $getResults)
        );
        \ksort($getValues);

        static::assertSame([
            'foo' => 'bar',
            'foo-timed' => 'bar',
        ], $getValues);

        sleep(5);

        /** @var PortalNodeStorageGetResult[] $getResults */
        $getResults = \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'foo',
        ]))));

        static::assertCount(1, $getResults);

        $getValues = \array_combine(
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getStorageKey(), $getResults),
            \array_map(static fn (PortalNodeStorageGetResult $g): string => $g->getValue(), $getResults)
        );
        \ksort($getValues);

        static::assertSame([
            'foo' => 'bar',
        ], $getValues);

        $delete->delete(new PortalNodeStorageDeleteCriteria($portalNodeKey, new StringCollection([
            'bar',
        ])));

        static::assertCount(1, \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'foo',
        ])))));

        $delete->delete(new PortalNodeStorageDeleteCriteria($portalNodeKey, new StringCollection([
            'foo',
        ])));

        static::assertCount(0, \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'foo',
        ])))));

        $set->set(new PortalNodeStorageSetPayload($portalNodeKey, new PortalNodeStorageSetItems([
            new PortalNodeStorageSetItem('foo', 'bar', 'string', null),
            new PortalNodeStorageSetItem('foo-timed', 'bar', 'string', new \DateInterval('PT5S')),
            new PortalNodeStorageSetItem('different', 'value', 'string', null),
        ])));

        $clear->clear(new PortalNodeStorageClearCriteria($portalNodeKey));

        static::assertCount(0, \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'foo',
        ])))));

        $portalNodeDelete->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$portalNodeKey])));
    }

    /**
     * Validates the usage of preview portal node keys.
     */
    public function testUsageOfPreviewPortalFails(): void
    {
        $facade = $this->createStorageFacade();
        $clear = $facade->getPortalNodeStorageClearAction();
        $delete = $facade->getPortalNodeStorageDeleteAction();
        $get = $facade->getPortalNodeStorageGetAction();
        $set = $facade->getPortalNodeStorageSetAction();
        $list = $facade->getPortalNodeStorageListAction();

        $portalNodeKey = new PreviewPortalNodeKey(PortalA::class());

        try {
            $set->set(new PortalNodeStorageSetPayload($portalNodeKey, new PortalNodeStorageSetItems([
                new PortalNodeStorageSetItem('foo', 'bar', 'string', null),
            ])));
            static::fail();
        } catch (UnsupportedStorageKeyException $exception) {
            static::assertSame(PreviewPortalNodeKey::class, $exception->getStorageKeyClass());
        }

        try {
            \iterable_to_array($get->get(new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
                'foo',
                'foo-timed',
            ]))));
            static::fail();
        } catch (UnsupportedStorageKeyException $exception) {
            static::assertSame(PreviewPortalNodeKey::class, $exception->getStorageKeyClass());
        }

        try {
            \iterable_to_array($list->list(new PortalNodeStorageListCriteria($portalNodeKey)));
            static::fail();
        } catch (UnsupportedStorageKeyException $exception) {
            static::assertSame(PreviewPortalNodeKey::class, $exception->getStorageKeyClass());
        }

        try {
            $delete->delete(new PortalNodeStorageDeleteCriteria($portalNodeKey, new StringCollection([
                'bar',
            ])));
            static::fail();
        } catch (UnsupportedStorageKeyException $exception) {
            static::assertSame(PreviewPortalNodeKey::class, $exception->getStorageKeyClass());
        }

        try {
            $clear->clear(new PortalNodeStorageClearCriteria($portalNodeKey));
            static::fail();
        } catch (UnsupportedStorageKeyException $exception) {
            static::assertSame(PreviewPortalNodeKey::class, $exception->getStorageKeyClass());
        }
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
