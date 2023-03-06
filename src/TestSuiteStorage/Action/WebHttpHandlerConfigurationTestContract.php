<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test web HTTP handler configuration related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class WebHttpHandlerConfigurationTestContract extends TestCase
{
    /**
     * Validates a complete web HTTP handler configuration "lifecycle" can be managed with the storage. It covers read, write, list and deletion of configuration entries.
     */
    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $createAction = $facade->getPortalNodeCreateAction();
        $deleteAction = $facade->getPortalNodeDeleteAction();
        $configFind = $facade->getWebHttpHandlerConfigurationFindAction();
        $configSet = $facade->getWebHttpHandlerConfigurationSetAction();

        $createPayloads = new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
        ]);
        $createResults = $createAction->create($createPayloads);
        $portalNodeKeys = new PortalNodeKeyCollection(\iterable_map(
            $createResults,
            static fn (PortalNodeCreateResult $r): PortalNodeKeyInterface => $r->getPortalNodeKey()
        ));

        static::assertCount(1, $createResults);
        $portalA = $createResults[0]->getPortalNodeKey();

        $findResult = $configFind->find(new WebHttpHandlerConfigurationFindCriteria($portalA, 'foo-bar', 'some-config'));

        static::assertNull($findResult->getValue());

        $configSet->set(new WebHttpHandlerConfigurationSetPayloads([
            new WebHttpHandlerConfigurationSetPayload($portalA, 'foo-bar', 'some-config', ['value' => 'some-value']),
        ]));

        $findResult = $configFind->find(new WebHttpHandlerConfigurationFindCriteria($portalA, 'foo-bar', 'some-config'));

        static::assertSame(['value' => 'some-value'], $findResult->getValue());

        $configSet->set(new WebHttpHandlerConfigurationSetPayloads([
            new WebHttpHandlerConfigurationSetPayload($portalA, 'foo-bar', 'some-config'),
        ]));
        $findResult = $configFind->find(new WebHttpHandlerConfigurationFindCriteria($portalA, 'foo-bar', 'some-config'));

        static::assertNull($findResult->getValue());

        $deleteAction->delete(new PortalNodeDeleteCriteria($portalNodeKeys));
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
