<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalC\PortalC;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test configuration of portal node related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class PortalNodeConfigurationTestContract extends TestCase
{
    private const PAYLOAD = [
        'object' => [
            'float' => 127.39,
            'integer' => 1230198274,
            'string' => '3408a5d7-39c9-4b66-9889-007d8ca78e85',
            'array' => [
                -17,
                -1,
                1,
                2,
                300,
            ],
        ],
    ];

    /**
     * Validates a complete portal node configuration "lifecycle" can be managed with the storage. It covers read, write, list and deletion of configuration entries.
     */
    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $createAction = $facade->getPortalNodeCreateAction();
        $deleteAction = $facade->getPortalNodeDeleteAction();
        $getAction = $facade->getPortalNodeConfigurationGetAction();
        $setAction = $facade->getPortalNodeConfigurationSetAction();
        $testPayload = self::PAYLOAD;
        \ksort($testPayload['object']);

        $createPayloads = new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
            new PortalNodeCreatePayload(PortalB::class()),
            new PortalNodeCreatePayload(PortalC::class()),
        ]);
        $createResults = $createAction->create($createPayloads);
        $portalNodeKeys = new PortalNodeKeyCollection($createResults->map(
            static fn (PortalNodeCreateResult $result): PortalNodeKeyInterface => $result->getPortalNodeKey()
        ));
        $getCriteria = new PortalNodeConfigurationGetCriteria($portalNodeKeys);

        static::assertCount(3, $createResults);

        foreach ($portalNodeKeys as $step => $portalNodeKey) {
            static::assertCount($step, \iterable_to_array(\iterable_filter(
                $getAction->get($getCriteria),
                static fn (PortalNodeConfigurationGetResult $result): bool => $result->getValue() !== []
            )));

            $setAction->set(new PortalNodeConfigurationSetPayloads([
                new PortalNodeConfigurationSetPayload($portalNodeKey, $testPayload),
            ]));

            static::assertCount($step + 1, \iterable_to_array(\iterable_filter(
                $getAction->get($getCriteria),
                static fn (PortalNodeConfigurationGetResult $result): bool => $result->getValue() !== []
            )));

            /** @var PortalNodeConfigurationGetResult[] $getResults */
            $getResults = \iterable_to_array($getAction->get(new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey]))));
            static::assertCount(1, $getResults);
            static::assertTrue($getResults[0]->getPortalNodeKey()->equals($portalNodeKey));

            $readConfiguration = $getResults[0]->getValue();

            static::assertArrayHasKey('object', $readConfiguration);
            static::assertIsArray($readConfiguration['object']);

            \ksort($readConfiguration['object']);
            static::assertSame($testPayload, $readConfiguration);
        }

        static::assertCount($portalNodeKeys->count(), \iterable_to_array(\iterable_filter(
            $getAction->get($getCriteria),
            static fn (PortalNodeConfigurationGetResult $result): bool => $result->getValue() !== []
        )));

        $setPayload = new PortalNodeConfigurationSetPayloads();

        foreach ($portalNodeKeys as $portalNodeKey) {
            $setPayload->push([new PortalNodeConfigurationSetPayload($portalNodeKey, null)]);
        }

        $setAction->set($setPayload);

        static::assertEmpty(\iterable_to_array(\iterable_filter(
            $getAction->get($getCriteria),
            static fn (PortalNodeConfigurationGetResult $result): bool => $result->getValue() !== []
        )));

        $deleteAction->delete(new PortalNodeDeleteCriteria($portalNodeKeys));
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
